<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get the orders count grouped by date
        $orders = Order::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare the data for the chart
        $labels = $orders->pluck('date')->toArray();
        $data = $orders->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Orders per day',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
