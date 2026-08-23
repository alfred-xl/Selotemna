<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InspectionRequest extends Model
{
    public const STATUS_NEW = 'new';

    /** @var list<string> */
    protected $fillable = [
        'submission_token',
        'status',
        'project_slug',
        'project_name',
        'full_name',
        'phone',
        'whatsapp',
        'email',
        'preferred_contact_method',
        'preferred_date',
        'preferred_time',
        'message',
        'consented_at',
        'staff_notified_at',
        'notification_failed_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (InspectionRequest $inspection): void {
            $inspection->reference ??= static::makeReference();
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'consented_at' => 'datetime',
            'staff_notified_at' => 'datetime',
            'notification_failed_at' => 'datetime',
        ];
    }

    private static function makeReference(): string
    {
        do {
            $reference = 'INS-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
        } while (static::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
