<?php

use App\Mail\ProjectEnquiryMail;
use App\Models\ProjectEnquiry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

function validProjectEnquiryData(): array
{
    return [
        'submission_token' => (string) Str::uuid(),
        'plot_option' => '500',
        'payment_preference' => 'Instalment',
        'purchase_timeline' => '1–3 months',
        'full_name' => 'Ada Buyer',
        'phone' => '+2348012345678',
        'email' => 'ada@example.test',
        'whatsapp' => '',
        'contact_method' => 'Email',
        'message' => 'Please share the next steps.',
        'consent' => '1',
    ];
}

it('renders the Omu Creek plot enquiry as the primary modal flow', function () {
    $content = $this->get(route('omu-creek'))->assertOk()->getContent();

    expect($content)->toContain('data-project-enquiry-dialog')
        ->toContain('data-project-enquiry-form')
        ->toContain('Select a Plot Size')
        ->toContain('300 sqm')
        ->toContain('500 sqm')
        ->toContain('1,000 sqm')
        ->toContain('Not sure yet')
        ->toContain('Payment preference')
        ->toContain('purchase_timeline')
        ->toContain('Request an Inspection')
        ->toContain('Selecting an option does not reserve a plot')
        ->toContain('data-async-success="project-enquiry"');

    $this->get(route('project-enquiries.create'))
        ->assertOk()
        ->assertSee('Omu Creek Plot Enquiry')
        ->assertSee('This is an enquiry, not a reservation.');
});

it('stores and notifies staff of a qualified Omu Creek plot enquiry', function () {
    Mail::fake();
    config()->set('mail.default', 'smtp');
    config()->set('selotemna.email', 'sales@selotemna.test');
    config()->set('selotemna.emails.primary', 'sales@selotemna.test');
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    $this->postJson(route('project-enquiries.store'), validProjectEnquiryData())
        ->assertCreated()
        ->assertJsonPath('receipt.project', 'Omu Creek')
        ->assertJsonPath('receipt.plot', '500 sqm — Standard plot')
        ->assertJsonPath('receipt.timeline', '1–3 months');

    $enquiry = ProjectEnquiry::query()->sole();

    expect($enquiry->status)->toBe(ProjectEnquiry::STATUS_NEW)
        ->and($enquiry->plot_size_sqm)->toBe(500)
        ->and($enquiry->price_snapshot)->toBe(25000000)
        ->and($enquiry->payment_preference)->toBe('Instalment')
        ->and($enquiry->project)->not->toBeNull()
        ->and($enquiry->staff_notified_at)->not->toBeNull();

    Mail::assertSent(ProjectEnquiryMail::class, fn (ProjectEnquiryMail $mail): bool => $mail->hasTo('sales@selotemna.test'));
});

it('returns exact validation errors and prevents duplicate submissions', function () {
    $data = validProjectEnquiryData();

    $this->postJson(route('project-enquiries.store'), $data)->assertCreated();
    $this->postJson(route('project-enquiries.store'), $data)->assertCreated();

    expect(ProjectEnquiry::query()->count())->toBe(1);

    $this->postJson(route('project-enquiries.store'), [
        'submission_token' => (string) Str::uuid(),
        'plot_option' => '700',
        'payment_preference' => '',
        'purchase_timeline' => '',
        'full_name' => '',
        'phone' => '12',
        'contact_method' => 'Email',
        'email' => 'invalid',
    ])->assertUnprocessable()->assertJsonValidationErrors([
        'plot_option',
        'payment_preference',
        'purchase_timeline',
        'full_name',
        'phone',
        'email',
        'consent',
    ]);
});
