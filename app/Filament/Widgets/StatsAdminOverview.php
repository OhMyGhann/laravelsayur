<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count())
                ->description('All users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Pending Orders', Order::query()->where('status', 'pending')->count())
                ->description('All Pending Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),

            Stat::make('Success Orders', Order::query()->where('status', 'success')->count())
                ->description('All Success Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

        ];
    }
}
