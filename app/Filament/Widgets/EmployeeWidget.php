<?php

namespace App\Filament\Widgets;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', User::count()),
        ];
    }

    // Employee role can view above cards on dashboard
    public static function canView(): bool 
    {
        $user = auth()->user();
        return $user->role_id === Role::where('name', 'Employee')->value('id');
    }
}
 