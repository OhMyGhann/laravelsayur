<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\OrderResource\RelationManagers;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Order list';

    protected static bool $canCreate = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Details')
                        ->schema([
                            Forms\Components\TextInput::make('order_number')
                                ->default('SY-' . random_int(100000, 9999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Forms\Components\TextInput::make('shipping_price')
                                ->label('Shipping Costs')
                                ->dehydrated()
                                ->disabledOn('edit')
                                ->numeric()
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => OrderStatusEnum::PENDING->value,
                                    'processing' => OrderStatusEnum::PROCESSING->value,
                                    'completed' => OrderStatusEnum::COMPLETED->value,
                                    'declined' => OrderStatusEnum::DECLINED->value,
                                ])
                                ->disabledOn('edit')
                                ->native(false)
                                ->required(),

                            Forms\Components\TextInput::make('no_hp')
                                ->label('No hp')
                                ->required(),
                            Forms\Components\TextInput::make('note')
                                ->label('Catatan')
                                ->required(),
                            Forms\Components\MarkdownEditor::make('alamat')
                                ->label('Alamat')
                                ->columnSpanFull(),
                        ])->columns(2),
                    Forms\Components\Wizard\Step::make('Order Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship('items')
                                ->schema([
                                    Forms\Components\Select::make('item_id')
                                        ->label('Product')
                                        ->options(Item::query()->pluck('name', 'id'))
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                                        $set('unit_price', Item::find($state)?->harga ?? 0)),

                                    Forms\Components\TextInput::make('quantity')
                                        ->numeric()
                                        ->live()
                                        ->dehydrated()
                                        ->default(1)
                                        ->required(),

                                    Forms\Components\TextInput::make('unit_price')
                                        ->label('Unit Price')
                                        ->disabled()
                                        ->dehydrated()
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\Placeholder::make('total_price')
                                        ->label('Total Price')
                                        ->content(function ($get) {
                                            return $get('quantity') * $get('unit_price');
                                        })
                                ])->columns(4)
                                ->disabledOn('edit')
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('bukti_tf')
                    ->label('Paid')
                    ->boolean()
                    ->icon(fn($state, $record): string => match (true) {
                        is_null($record->bukti_tf) || $record->bukti_tf === 'no' => 'heroicon-o-x-circle',
                        !is_null($record->bukti_tf) && $record->bukti_tf !== 'no' => 'heroicon-o-check-circle',
                    })
                    ->color(fn($state, $record): string => match (true) {
                        is_null($record->bukti_tf) || $record->bukti_tf === 'no' => 'warning',
                        !is_null($record->bukti_tf) && $record->bukti_tf !== 'no' => 'success',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->url(fn(Order $record) => route('order.pdf.download', $record))
                    ->openUrlInNewTab()
                    ->visible(fn(Order $record) => $record->status === 'completed'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Order::query()
            ->whereHas('user', function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
