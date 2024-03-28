<?php

namespace App\Filament\Widgets;

use App\Models\leave;
use App\Models\leavepermission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class AdminWidgets extends BaseWidget
{
    protected function getStats(): array
    {

        $requestedCount = Leave::where('leavepermission_id', leavepermission::where('name', 'Requested')->value('id'))->count();
        $approvedCount = Leave::where('leavepermission_id', Leavepermission::where('name', 'Approved')->value('id'))->count();
        $declinedCount = Leave::where('leavepermission_id', Leavepermission::where('name', 'Declined')->value('id'))->count();
        
        $approvedLeavesToday = Leave::whereDate('date', today())
            ->where('leavepermission_id', Leavepermission::where('name', 'Approved')->value('id'))
            ->count();

        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Users Registered Today', User::whereDate('created_at', today())->count()),
            Stat::make('Leaves Approved Today', $approvedLeavesToday),
            // Stat::make('Leaves Requested', $requestedCount),
            // Stat::make('Leaves Approved', $approvedCount),
            // Stat::make('Leaves Declined', $declinedCount),
        ];
    }

    // Admin role can view above cards on dashboard
    public static function canView(): bool 
    {
        $user = auth()->user();
        return $user->role_id === Role::where('name', 'super_admin')->value('id');
    }

    
}
