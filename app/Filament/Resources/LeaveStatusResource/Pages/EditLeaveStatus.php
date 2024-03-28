<?php

namespace App\Filament\Resources\LeaveStatusResource\Pages;

use App\Filament\Resources\LeaveStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveStatus extends EditRecord
{
    protected static string $resource = LeaveStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
