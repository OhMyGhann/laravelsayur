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
                ->label('Total pengguna')
                ->description('Jumlah Pengguna aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Pending Orders', Order::query()->where('status', 'pending')->count())
                ->label('Order tertunda')
                ->description('Jumlah order tertunda')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),

            Stat::make('Success Orders', Order::query()->where('status', 'success')->count())
                ->label('Order sukses')
                ->description('Jumlah order sukses')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

        ];
    }
}
