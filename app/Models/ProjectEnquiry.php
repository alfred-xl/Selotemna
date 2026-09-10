<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProjectEnquiry extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_QUALIFIED = 'qualified';

    public const STATUS_INSPECTION_SCHEDULED = 'inspection_scheduled';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'project_id',
        'submission_token',
        'status',
        'project_slug',
        'project_name',
        'plot_size_sqm',
        'plot_label',
        'price_snapshot',
        'currency',
        'payment_preference',
        'purchase_timeline',
        'full_name',
        'phone',
        'email',
        'whatsapp',
        'preferred_contact_method',
        'message',
        'admin_notes',
        'handled_at',
        'consented_at',
        'staff_notified_at',
        'notification_failed_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProjectEnquiry $enquiry): void {
            $enquiry->reference ??= static::makeReference();
        });
    }

    protected function casts(): array
    {
        return [
            'plot_size_sqm' => 'integer',
            'price_snapshot' => 'integer',
            'handled_at' => 'datetime',
            'consented_at' => 'datetime',
            'staff_notified_at' => 'datetime',
            'notification_failed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function paymentReceipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    protected function pricePerSqmSnapshot(): Attribute
    {
        return Attribute::get(function (): ?int {
            if (! $this->plot_size_sqm || ! $this->price_snapshot) {
                return null;
            }

            return (int) round($this->price_snapshot / $this->plot_size_sqm);
        });
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_QUALIFIED => 'Qualified',
            self::STATUS_INSPECTION_SCHEDULED => 'Inspection scheduled',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    private static function makeReference(): string
    {
        do {
            $reference = 'PRJ-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
        } while (static::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
