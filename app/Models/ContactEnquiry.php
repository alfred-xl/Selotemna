<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContactEnquiry extends Model
{
    public const STATUS_NEW = 'new';

    /** @var list<string> */
    protected $fillable = [
        'submission_token',
        'status',
        'full_name',
        'phone',
        'email',
        'whatsapp',
        'preferred_contact_method',
        'enquiry_type',
        'project_type',
        'proposed_location',
        'project_stage',
        'scope_summary',
        'message',
        'consented_at',
        'staff_notified_at',
        'notification_failed_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (ContactEnquiry $enquiry): void {
            $enquiry->reference ??= static::makeReference();
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'consented_at' => 'datetime',
            'staff_notified_at' => 'datetime',
            'notification_failed_at' => 'datetime',
        ];
    }

    private static function makeReference(): string
    {
        do {
            $reference = 'ENQ-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
        } while (static::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
