<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_id', 'label', 'size_sqm', 'price', 'currency', 'sort_order'])]
class ProjectPlotOption extends Model
{
    protected function casts(): array
    {
        return [
            'size_sqm' => 'decimal:2',
            'price' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
