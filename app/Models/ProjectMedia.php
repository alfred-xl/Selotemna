<?php

namespace App\Models;

use App\Enums\ProjectMediaKind;
use App\Enums\ProjectMediaRole;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'project_id',
    'kind',
    'role',
    'disk',
    'path',
    'external_url',
    'mime_type',
    'alt_text',
    'caption',
    'credit',
    'sort_order',
])]
class ProjectMedia extends Model
{
    protected $table = 'project_media';

    protected function casts(): array
    {
        return [
            'kind' => ProjectMediaKind::class,
            'role' => ProjectMediaRole::class,
            'sort_order' => 'integer',
        ];
    }

    protected function sourceUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->external_url) {
                return $this->external_url;
            }

            if (! $this->path) {
                return null;
            }

            $publicPath = ltrim(str_replace('\\', '/', $this->path), '/');

            return is_file(public_path($publicPath))
                ? asset($publicPath)
                : Storage::disk($this->disk)->url($this->path);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
