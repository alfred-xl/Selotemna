<?php

namespace App\Filament\Resources\Projects\Pages\Concerns;

use App\Enums\ProjectPublicationStatus;
use Illuminate\Validation\ValidationException;

trait ValidatesProjectPublication
{
    protected function ensureProjectIsReadyForPublication(bool $revertToDraft = false): void
    {
        $project = $revertToDraft ? $this->record->fresh() : $this->record;

        if ($project->publication_status !== ProjectPublicationStatus::Published) {
            return;
        }

        $issues = $project->publicationIssues();

        if ($issues === []) {
            return;
        }

        if ($revertToDraft) {
            $project->forceFill([
                'publication_status' => ProjectPublicationStatus::Draft,
                'is_publication_verified' => false,
            ])->saveQuietly();
        }

        throw ValidationException::withMessages([
            'data.publication_status' => 'Publication blocked: '.implode(' ', $issues),
        ]);
    }
}
