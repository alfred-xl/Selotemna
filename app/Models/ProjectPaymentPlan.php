<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_id', 'initial_deposit', 'currency', 'balance_period', 'note'])]
class ProjectPaymentPlan extends Model
{
    protected function casts(): array
    {
        return ['initial_deposit' => 'integer'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
