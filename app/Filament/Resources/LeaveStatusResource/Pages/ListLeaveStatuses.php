<?php

namespace App\Filament\Resources\LeaveStatusResource\Pages;

use App\Filament\Resources\LeaveStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveStatuses extends ListRecords
{
    protected static string $resource = LeaveStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
