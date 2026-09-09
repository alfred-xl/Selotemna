<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Actions\PreviewProjectAction;
use App\Filament\Resources\Projects\Pages\Concerns\ValidatesProjectPublication;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    use ValidatesProjectPublication;

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewProjectAction::make(),
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->ensureProjectIsReadyForPublication(revertToDraft: true);
    }

    protected function beforeSave(): void
    {
        $this->ensureProjectIsReadyForPublication();
    }
}
