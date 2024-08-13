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
use Filament\Forms\Components\FileUpload;
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
                    Forms\Components\Wizard\Step::make('Detail order')
                        ->schema([
                            Forms\Components\TextInput::make('order_number')
                                ->label('No Order')
                                ->default('SY-' . random_int(100000, 9999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Forms\Components\Select::make('user_id')
                                ->label('Pengguna')
                                ->relationship('user', 'name')
                                ->disabledOn('edit')
                                ->searchable()
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => 'Menunggu',
                                    'processing' => 'Sedang Diproses',
                                    'packed' => 'Sedang di Kemas',
                                    'completed' => 'Selesai',
                                    'declined' => 'Ditolak',

                                ])
                                ->native(false)
                                ->required(),
                            FileUpload::make('bukti_tf')
                                ->label('Bukti Transfer'),
                            Forms\Components\TextInput::make('no_hp')
                                ->label('No hp')
                                ->required(),
                            Forms\Components\TextInput::make('note')
                                ->label('Catatan')
                                ->required(),
                            Forms\Components\MarkdownEditor::make('alamat')
                                ->label('Alamat')
                                ->columnSpanFull(),
                        ])->columns(3),
                    Forms\Components\Wizard\Step::make('Order Produk')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->label('Produk')
                                ->relationship('items')
                                ->schema([
                                    Forms\Components\Select::make('item_id')
                                        ->label('Produk')
                                        ->options(Item::query()->where('stok', '>', 0)->pluck('name', 'id'))
                                        ->required()
                                        ->disabledOn('edit')
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
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
                                                        ->title('Produk ini sudah habis')
                                                        ->danger()
                                                        ->send();
                                                } elseif ($state > $item->stok) {
                                                    $set('quantity', $item->stok);
                                                    Notification::make()
                                                        ->title('Jumlah melebihi stok yang tersedia')
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
                                        ->label('Harga per produk')
                                        ->readonly(),
                                ])
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                })
                                ->deleteAction(
                                    fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
                                )
                                ->reorderable(false)
                                ->columns(4),
                            Forms\Components\Section::make('Total')
                                ->schema([
                                    Forms\Components\TextInput::make('shipping_price')
                                        ->label('Ongkos kirim')
                                        ->dehydrated()
                                        ->numeric()
                                        ->reactive()
                                        ->required()
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                            self::updateTotals($get, $set);
                                        }),
                                    Forms\Components\TextInput::make('total_price')
                                        ->label('Total harga')
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
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return match ($record->status) {
                            'pending' => 'Menunggu',
                            'processing' => 'Sedang Diproses',
                            'packed' => 'Sedang di Kemas',
                            'completed' => 'Selesai',
                            'declined' => 'Ditolak',
                            default => $record->status,
                        };
                    }),
                Tables\Columns\IconColumn::make('bukti_tf')
                    ->label('Bukti Transfer')
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
                    ->label('Tanggal Pesanan')
                    ->date(),
            ])->defaultSort('created_at', 'desc')
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
                        ->url(fn(Order $record) => route('order.pdf.download', $record))
                        ->openUrlInNewTab()
                        ->visible(fn(Order $record) => $record->status === 'completed')
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

    public static function getNavigationBadge(): ?string
    {
        return Order::where('status', 'pending')->count();
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
