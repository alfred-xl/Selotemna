<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\Pages\Concerns\ValidatesProjectPublication;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use ValidatesProjectPublication;

    protected static string $resource = ProjectResource::class;

    protected function afterCreate(): void
    {
        $this->ensureProjectIsReadyForPublication(revertToDraft: true);
    }
}
