<?php

namespace App\Filament\Resources\LeavePermissionResource\Pages;

use App\Filament\Resources\LeavePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeavePermissions extends ListRecords
{
    protected static string $resource = LeavePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
