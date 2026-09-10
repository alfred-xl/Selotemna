<?php

namespace App\Models;

use App\Support\NairaAmountInWords;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

#[Fillable([
    'project_enquiry_id',
    'project_id',
    'customer_name',
    'customer_phone',
    'customer_email',
    'project_name',
    'project_slug',
    'plot_size_sqm',
    'plot_label',
    'currency',
    'amount_received',
    'payment_purpose',
    'payment_method',
    'payment_date',
    'transaction_reference',
    'balance_remaining',
    'notes',
    'created_by',
])]
class PaymentReceipt extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_ISSUED = 'issued';

    public const STATUS_VOIDED = 'voided';

    protected static function booted(): void
    {
        static::updating(function (PaymentReceipt $receipt): void {
            $originalStatus = $receipt->getRawOriginal('status');

            if ($originalStatus === self::STATUS_VOIDED) {
                throw new DomainException('A voided receipt cannot be changed.');
            }

            if ($originalStatus !== self::STATUS_ISSUED) {
                return;
            }

            $allowedChanges = ['status', 'voided_by', 'voided_at', 'void_reason', 'updated_at'];
            $hasUnexpectedChanges = array_diff(array_keys($receipt->getDirty()), $allowedChanges) !== [];

            if ($receipt->status !== self::STATUS_VOIDED || $hasUnexpectedChanges || blank($receipt->void_reason)) {
                throw new DomainException('An issued receipt cannot be changed. Void it with a recorded reason instead.');
            }
        });

        static::deleting(function (): never {
            throw new DomainException('Payment receipts cannot be deleted.');
        });
    }

    protected function casts(): array
    {
        return [
            'plot_size_sqm' => 'integer',
            'amount_received' => 'integer',
            'balance_remaining' => 'integer',
            'payment_date' => 'date',
            'issued_at' => 'datetime',
            'voided_at' => 'datetime',
        ];
    }

    protected function amountInWords(): Attribute
    {
        return Attribute::get(fn (): string => NairaAmountInWords::convert($this->amount_received));
    }

    public function projectEnquiry(): BelongsTo
    {
        return $this->belongsTo(ProjectEnquiry::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isIssued(): bool
    {
        return $this->status === self::STATUS_ISSUED;
    }

    public function isVoided(): bool
    {
        return $this->status === self::STATUS_VOIDED;
    }

    public function issue(User $administrator): self
    {
        DB::transaction(function () use ($administrator): void {
            $receipt = self::query()->lockForUpdate()->findOrFail($this->getKey());

            if (! $receipt->isDraft()) {
                throw new DomainException('Only a draft receipt can be issued.');
            }

            $issuedAt = now();
            $receipt->forceFill([
                'receipt_number' => sprintf('SLT-RCP-%s-%06d', $issuedAt->format('Y'), $receipt->getKey()),
                'status' => self::STATUS_ISSUED,
                'issued_by' => $administrator->getKey(),
                'issuer_name' => $administrator->name,
                'issued_at' => $issuedAt,
            ])->save();
        });

        return $this->refresh();
    }

    public function void(User $administrator, string $reason): self
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw new InvalidArgumentException('A reason is required to void a receipt.');
        }

        DB::transaction(function () use ($administrator, $reason): void {
            $receipt = self::query()->lockForUpdate()->findOrFail($this->getKey());

            if (! $receipt->isIssued()) {
                throw new DomainException('Only an issued receipt can be voided.');
            }

            $receipt->forceFill([
                'status' => self::STATUS_VOIDED,
                'voided_by' => $administrator->getKey(),
                'voided_at' => now(),
                'void_reason' => $reason,
            ])->save();
        });

        return $this->refresh();
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ISSUED => 'Issued',
            self::STATUS_VOIDED => 'Voided',
        ];
    }
}
