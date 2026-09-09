<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'project_faq_group_id',
    'key',
    'question',
    'answer',
    'points',
    'note',
    'is_featured',
    'sort_order',
])]
class ProjectFaq extends Model
{
    protected function casts(): array
    {
        return [
            'points' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function faqGroup(): BelongsTo
    {
        return $this->belongsTo(ProjectFaqGroup::class, 'project_faq_group_id');
    }
}
