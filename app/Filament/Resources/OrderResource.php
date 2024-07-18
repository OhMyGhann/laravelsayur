<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transaksi';

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

                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => OrderStatusEnum::PENDING->value,
                                    'processing' => OrderStatusEnum::PROCESSING->value,
                                    'completed' => OrderStatusEnum::COMPLETED->value,
                                    'declined' => OrderStatusEnum::DECLINED->value,
                                ])
                                ->native(false)
                                ->required(),

                            Forms\Components\MarkdownEditor::make('note')
                                ->columnSpanFull()
                        ])->columns(3),
                    Forms\Components\Wizard\Step::make('Order Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship('items')
                                ->schema([
                                    Forms\Components\Select::make('item_id')
                                        ->label('Product')
                                        ->options(Item::query()->where('stok', '>', 0)->pluck('name', 'id'))
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                        $set('unit_price', Item::find($state)?->harga ?? 0)),

                                    Forms\Components\TextInput::make('quantity')
                                        ->numeric()
                                        ->reactive()
                                        ->dehydrated()
                                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                            $item_id = $get('item_id');
                                            $item = Item::find($item_id);

                                            if ($item) {
                                                if ($item->stok == 0) {
                                                    Notification::make()
                                                        ->title('This product is out of stock')
                                                        ->danger()
                                                        ->send();
                                                } elseif ($state > $item->stok) {
                                                    $set('quantity', $item->stok);
                                                    Notification::make()
                                                        ->title('Quantity exceeds available stock')
                                                        ->danger()
                                                        ->send();
                                                }

                                                $set(
                                                    'sub_total',
                                                    number_format($get('quantity') * $get('unit_price'), 2, '.', '')
                                                );
                                            }
                                        })
                                        ->rules(function ($state, $get) {
                                            $item_id = $get('item_id');
                                            $item = Item::find($item_id);
                                            return $item ? Rule::in(range(0, $item->stok)) : [];
                                        }),
                                    Forms\Components\TextInput::make('unit_price')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(),
                                    Forms\Components\TextInput::make('sub_total')
                                        ->label('Total Item Price')
                                        ->readonly(),
                                ])
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                })
                                ->deleteAction(
                                    fn (Action $action) => $action->after(fn (Get $get, Set $set) => self::updateTotals($get, $set)),
                                )
                                ->reorderable(false)
                                ->columns(4),
                            Forms\Components\Section::make('Total')
                                ->schema([
                                    Forms\Components\TextInput::make('shipping_price')
                                        ->label('Shipping Costs')
                                        ->dehydrated()
                                        ->numeric()
                                        ->reactive()
                                        ->required()
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                            self::updateTotals($get, $set);
                                        }),
                                    Forms\Components\TextInput::make('total_price')
                                        ->label('Order Total Price')
                                        ->numeric()
                                        ->live()
                                        ->readonly()
                                        ->prefix('Rp.')
                                        ->dehydrated()
                                ])->columns(2),

                        ])


                ])->columnSpanFull()
            ]);
    }

    protected static function updateTotals(Forms\Get $get, Forms\Set $set): void
    {
        $items = $get('items') ?? [];
        $shippingPrice = $get('shipping_price') ?? 0;

        $shippingPrice = floatval($shippingPrice);

        $totalItemsPrice = collect($items)->sum(function ($item) {
            return floatval($item['sub_total'] ?? 0);
        });

        $totalPrice = $totalItemsPrice + $shippingPrice;

        $set('total_price', number_format($totalPrice, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Invoice')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('info')
                        ->url(fn (Order $record) => route('order.pdf.download', $record))
                        ->openUrlInNewTab()
                        ->visible(fn (Order $record) => $record->status === 'completed')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}