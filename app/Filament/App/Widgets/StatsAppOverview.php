<?php

namespace App\Filament\App\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAppOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = auth()->user(); // Get the currently logged-in user

        return [

            Stat::make('Order tertunda', Order::query()
                ->where('status', 'pending')
                ->where('user_id', $user->id) // Filter by logged-in user's ID
                ->count())
                ->description('All Pending Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Order Sukses', Order::query()
                ->where('status', 'success')
                ->where('user_id', $user->id) // Filter by logged-in user's ID
                ->count())
                ->description('All Success Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

            Stat::make('Order dibatalkan', Order::query()
                ->where('status', 'declined')
                ->where('user_id', $user->id) // Filter by logged-in user's ID
                ->count())
                ->description('All failed Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
        ];
    }
}
