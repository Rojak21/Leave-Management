<?php

namespace App\Filament\Resources\LeavePermissionResource\Pages;

use App\Filament\Resources\LeavePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeavePermission extends EditRecord
{
    protected static string $resource = LeavePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
