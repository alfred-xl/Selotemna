<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_document_stage_id', 'name', 'sort_order'])]
class ProjectDocument extends Model
{
    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function documentStage(): BelongsTo
    {
        return $this->belongsTo(ProjectDocumentStage::class, 'project_document_stage_id');
    }
}
