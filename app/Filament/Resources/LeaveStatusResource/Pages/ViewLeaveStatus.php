<?php

namespace App\Filament\Resources\LeaveStatusResource\Pages;

use App\Filament\Resources\LeaveStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLeaveStatus extends ViewRecord
{
    protected static string $resource = LeaveStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
