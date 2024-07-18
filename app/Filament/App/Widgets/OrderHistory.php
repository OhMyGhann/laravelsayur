<?php

namespace App\Filament\App\Widgets;

use Filament\Tables;
use Pages\ViewOrder;
use Pages\ListOrders;
use Filament\Tables\Table;
use App\Filament\App\Resources\OrderResource;
use Filament\Widgets\TableWidget as BaseWidget;

class OrderHistory extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderResource::getEloquentQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('bukti_tf')
                    ->label('Bukti Transfer')
                    ->boolean()
                    ->icon(fn ($state, $record): string => match (true) {
                        is_null($record->bukti_tf) || $record->bukti_tf === 'no' => 'heroicon-o-x-circle',
                        !is_null($record->bukti_tf) && $record->bukti_tf !== 'no' => 'heroicon-o-check-circle',
                    })
                    ->color(fn ($state, $record): string => match (true) {
                        is_null($record->bukti_tf) || $record->bukti_tf === 'no' => 'warning',
                        !is_null($record->bukti_tf) && $record->bukti_tf !== 'no' => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date(),
            ]);
    }
}
