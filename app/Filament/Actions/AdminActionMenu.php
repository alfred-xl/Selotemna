<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

final class AdminActionMenu
{
    /**
     * @param  array<Action | ActionGroup>  $actions
     */
    public static function make(array $actions): ActionGroup
    {
        return ActionGroup::make($actions)
            ->label('Actions')
            ->icon('heroicon-o-ellipsis-horizontal-circle')
            ->button()
            ->dropdownPlacement('bottom-end');
    }
}
