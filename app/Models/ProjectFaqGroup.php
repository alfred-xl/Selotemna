<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['project_id', 'slug', 'label', 'sort_order'])]
class ProjectFaqGroup extends Model
{
    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProjectFaq::class)->orderBy('sort_order');
    }
}
