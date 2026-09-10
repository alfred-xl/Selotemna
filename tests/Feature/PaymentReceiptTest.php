<?php

use App\Filament\Resources\PaymentReceipts\Pages\CreatePaymentReceipt;
use App\Filament\Resources\PaymentReceipts\Pages\ListPaymentReceipts;
use App\Filament\Resources\PaymentReceipts\Pages\ViewPaymentReceipt;
use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Filament\Resources\ProjectEnquiries\Pages\ViewProjectEnquiry;
use App\Models\PaymentReceipt;
use App\Models\ProjectEnquiry;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Livewire;

function createReceiptEnquiry(array $attributes = []): ProjectEnquiry
{
    return ProjectEnquiry::query()->create(array_merge([
        'submission_token' => (string) Str::uuid(),
        'status' => ProjectEnquiry::STATUS_QUALIFIED,
        'project_slug' => 'omu-creek',
        'project_name' => 'Omu Creek',
        'plot_size_sqm' => 650,
        'plot_label' => 'Custom plot — 650 sqm',
        'price_snapshot' => 32500000,
        'currency' => 'NGN',
        'payment_preference' => 'Outright',
        'purchase_timeline' => 'Immediately',
        'full_name' => 'Ada Customer',
        'phone' => '+2348012345678',
        'email' => 'ada@example.test',
        'preferred_contact_method' => 'Telephone',
        'consented_at' => now(),
    ], $attributes));
}

function createDraftReceipt(User $administrator, array $attributes = []): PaymentReceipt
{
    return PaymentReceipt::query()->create(array_merge([
        'status' => PaymentReceipt::STATUS_DRAFT,
        'customer_name' => 'Ada Customer',
        'customer_phone' => '+2348012345678',
        'customer_email' => 'ada@example.test',
        'project_name' => 'Omu Creek',
        'project_slug' => 'omu-creek',
        'plot_size_sqm' => 650,
        'plot_label' => 'Custom plot — 650 sqm',
        'currency' => 'NGN',
        'amount_received' => 32500000,
        'payment_purpose' => 'Full payment for Omu Creek land',
        'payment_method' => 'Bank transfer',
        'payment_date' => today(),
        'transaction_reference' => 'TRX-001234',
        'balance_remaining' => 0,
        'created_by' => $administrator->getKey(),
    ], $attributes));
}

it('creates a draft receipt from an enquiry-prefilled admin form', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $enquiry = createReceiptEnquiry();

    $this->actingAs($administrator);

    Livewire::withQueryParams(['project_enquiry' => $enquiry->getKey()])
        ->test(CreatePaymentReceipt::class)
        ->assertSchemaStateSet([
            'project_enquiry_id' => $enquiry->getKey(),
            'customer_name' => 'Ada Customer',
            'customer_phone' => '+2348012345678',
            'customer_email' => 'ada@example.test',
            'project_name' => 'Omu Creek',
            'plot_size_sqm' => 650,
            'plot_label' => 'Custom plot — 650 sqm',
            'currency' => 'NGN',
        ])
        ->fillForm([
            'amount_received' => 10000000,
            'payment_purpose' => 'Deposit for Omu Creek land',
            'payment_method' => 'Bank transfer',
            'payment_date' => today()->toDateString(),
            'transaction_reference' => 'TRX-PART-001',
            'balance_remaining' => 22500000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $receipt = PaymentReceipt::query()->sole();

    expect($receipt->status)->toBe(PaymentReceipt::STATUS_DRAFT)
        ->and($receipt->receipt_number)->toBeNull()
        ->and($receipt->project_enquiry_id)->toBe($enquiry->getKey())
        ->and($receipt->created_by)->toBe($administrator->getKey())
        ->and($receipt->amount_received)->toBe(10000000)
        ->and($receipt->balance_remaining)->toBe(22500000);
});

it('validates required confirmed-payment details before creating a receipt', function () {
    $administrator = User::factory()->create(['is_admin' => true]);

    $this->actingAs($administrator);

    Livewire::test(CreatePaymentReceipt::class)
        ->fillForm([
            'customer_name' => '',
            'customer_phone' => '',
            'project_name' => '',
            'amount_received' => 0,
            'payment_purpose' => '',
            'payment_method' => null,
            'payment_date' => today()->addDay()->toDateString(),
        ])
        ->call('create')
        ->assertHasFormErrors([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'project_name' => 'required',
            'amount_received' => 'min',
            'payment_purpose' => 'required',
            'payment_method' => 'required',
            'payment_date' => 'before_or_equal',
        ]);

    expect(PaymentReceipt::query()->doesntExist())->toBeTrue();
});

it('issues an auditable uniquely numbered receipt only once', function () {
    $administrator = User::factory()->create(['is_admin' => true, 'name' => 'Receipt Administrator']);
    $receipt = createDraftReceipt($administrator);

    $receipt->issue($administrator);

    expect($receipt->status)->toBe(PaymentReceipt::STATUS_ISSUED)
        ->and($receipt->receipt_number)->toBe(sprintf('SLT-RCP-%s-%06d', now()->format('Y'), $receipt->getKey()))
        ->and($receipt->issued_by)->toBe($administrator->getKey())
        ->and($receipt->issuer_name)->toBe('Receipt Administrator')
        ->and($receipt->issued_at)->not->toBeNull()
        ->and($receipt->amount_in_words)->toBe('Thirty two million five hundred thousand naira only');

    expect(fn () => $receipt->issue($administrator))->toThrow(DomainException::class, 'Only a draft receipt can be issued.');
});

it('keeps issued and voided receipts immutable and non-deletable', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $receipt = createDraftReceipt($administrator)->issue($administrator);
    $originalAmount = $receipt->amount_received;

    expect(fn () => $receipt->update(['amount_received' => 1]))
        ->toThrow(DomainException::class, 'An issued receipt cannot be changed.');
    expect($receipt->fresh()->amount_received)->toBe($originalAmount);

    $receipt = $receipt->fresh()->void($administrator, 'The bank reversed this payment.');

    expect(fn () => $receipt->update(['notes' => 'Changed after voiding']))
        ->toThrow(DomainException::class, 'A voided receipt cannot be changed.');
    expect(fn () => $receipt->fresh()->delete())
        ->toThrow(DomainException::class, 'Payment receipts cannot be deleted.');
    expect(PaymentReceipt::query()->whereKey($receipt)->exists())->toBeTrue();
});

it('supports multiple payment receipts for one enquiry without duplicate numbers', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $enquiry = createReceiptEnquiry();
    $first = createDraftReceipt($administrator, ['project_enquiry_id' => $enquiry->getKey(), 'amount_received' => 10000000]);
    $second = createDraftReceipt($administrator, [
        'project_enquiry_id' => $enquiry->getKey(),
        'amount_received' => 5000000,
        'transaction_reference' => 'TRX-SECOND',
    ]);

    $first->issue($administrator);
    $second->issue($administrator);

    expect($enquiry->paymentReceipts()->count())->toBe(2)
        ->and($first->receipt_number)->not->toBe($second->receipt_number);
});

it('voids an issued receipt with a required reason and retained audit data', function () {
    $issuer = User::factory()->create(['is_admin' => true, 'name' => 'Issuing Admin']);
    $voidingAdministrator = User::factory()->create(['is_admin' => true, 'name' => 'Voiding Admin']);
    $receipt = createDraftReceipt($issuer)->issue($issuer);
    $receiptNumber = $receipt->receipt_number;

    expect(fn () => $receipt->void($voidingAdministrator, ''))->toThrow(InvalidArgumentException::class);

    $receipt->void($voidingAdministrator, 'Duplicate bank transaction was recorded.');

    expect($receipt->status)->toBe(PaymentReceipt::STATUS_VOIDED)
        ->and($receipt->receipt_number)->toBe($receiptNumber)
        ->and($receipt->issued_by)->toBe($issuer->getKey())
        ->and($receipt->voided_by)->toBe($voidingAdministrator->getKey())
        ->and($receipt->void_reason)->toBe('Duplicate bank transaction was recorded.')
        ->and($receipt->voided_at)->not->toBeNull();

    expect(fn () => $receipt->void($voidingAdministrator, 'A second reason.'))->toThrow(DomainException::class, 'Only an issued receipt can be voided.');
});

it('protects receipt screens and document endpoints for administrators', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $ordinaryUser = User::factory()->create(['is_admin' => false]);
    $receipt = createDraftReceipt($administrator);

    $this->get(PaymentReceiptResource::getUrl('index'))->assertRedirect();
    $this->actingAs($ordinaryUser)->get(route('admin.e-receipts.preview', $receipt))->assertForbidden();
    $this->actingAs($ordinaryUser)->get(route('admin.e-receipts.download', $receipt))->assertForbidden();

    $this->actingAs($administrator)
        ->get(PaymentReceiptResource::getUrl('index'))
        ->assertOk()
        ->assertSee('E-Receipts');
    $this->actingAs($administrator)
        ->get(route('admin.e-receipts.preview', $receipt))
        ->assertOk()
        ->assertSee('DRAFT')
        ->assertSee('not proof of payment');
    $this->actingAs($administrator)
        ->get(route('admin.e-receipts.download', $receipt))
        ->assertStatus(409);
});

it('renders an official PDF for an issued receipt and a void-marked PDF after voiding', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $receipt = createDraftReceipt($administrator)->issue($administrator);

    $response = $this->actingAs($administrator)->get(route('admin.e-receipts.download', $receipt));

    $response->assertOk()->assertHeader('content-type', 'application/pdf');
    expect($response->getContent())->toStartWith('%PDF');

    $receipt->void($administrator, 'Payment was reversed by the bank.');

    $this->actingAs($administrator)
        ->get(route('admin.e-receipts.preview', $receipt))
        ->assertOk()
        ->assertSee('VOID')
        ->assertSee('Payment was reversed by the bank.');

    $this->actingAs($administrator)
        ->get(route('admin.e-receipts.download', $receipt))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('connects the enquiry action and receipt management pages', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $enquiry = createReceiptEnquiry();
    $receipt = createDraftReceipt($administrator, ['project_enquiry_id' => $enquiry->getKey()]);

    $this->actingAs($administrator);

    Livewire::test(ViewProjectEnquiry::class, ['record' => $enquiry->getRouteKey()])
        ->assertActionExists('generateReceipt', fn ($action): bool => $action->getUrl() === PaymentReceiptResource::getUrl('create', [
            'project_enquiry' => $enquiry->getKey(),
        ]));

    Livewire::test(ListPaymentReceipts::class)
        ->assertSee('Actions')
        ->assertTableActionExists('previewReceipt', record: $receipt)
        ->assertTableActionHidden('downloadReceipt', record: $receipt);

    Livewire::test(ViewPaymentReceipt::class, ['record' => $receipt->getRouteKey()])
        ->assertActionVisible('issueReceipt')
        ->assertActionHidden('voidReceipt')
        ->assertActionHidden('downloadReceipt')
        ->callAction('issueReceipt')
        ->assertNotified('Receipt issued');

    expect($receipt->refresh()->isIssued())->toBeTrue();
    $this->get(PaymentReceiptResource::getUrl('edit', ['record' => $receipt]))->assertForbidden();

    Livewire::test(ViewPaymentReceipt::class, ['record' => $receipt->getRouteKey()])
        ->assertActionHidden('issueReceipt')
        ->assertActionVisible('voidReceipt')
        ->assertActionVisible('downloadReceipt')
        ->callAction('voidReceipt', ['reason' => 'The confirmed payment was reversed.'])
        ->assertNotified('Receipt voided');

    expect($receipt->refresh()->isVoided())->toBeTrue()
        ->and($receipt->void_reason)->toBe('The confirmed payment was reversed.');
});
