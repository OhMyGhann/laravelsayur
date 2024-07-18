<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\PembelianResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianResource\RelationManagers;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Order';

    protected static ?string $navigationLabel = 'Order';

    protected static bool $shouldRegisterNavigation = false;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Details')
                        ->schema([
                            Forms\Components\TextInput::make('order_id')
                                ->default('SY-' . random_int(100000, 9999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Forms\Components\Select::make('customer_id')
                                ->relationship('customer', 'name')
                                ->searchable()
                                ->required(),

                            Forms\Components\TextInput::make('shipping_price')
                                ->label('Shipping Costs')
                                ->dehydrated()
                                ->numeric()
                                ->required(),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'pending' => OrderStatusEnum::PENDING->value,
                                    'processing' => OrderStatusEnum::PROCESSING->value,
                                    'completed' => OrderStatusEnum::COMPLETED->value,
                                    'declined' => OrderStatusEnum::DECLINED->value,
                                ])->required(),

                            Forms\Components\MarkdownEditor::make('notes')
                                ->columnSpanFull()
                        ])->columns(2),
                    Forms\Components\Wizard\Step::make('Order Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('product_id')
                                        ->label('Product')
                                        ->options(Item::query()->pluck('name', 'id'))
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                        $set('unit_price', Item::find($state)?->price ?? 0)),

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
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Item')
                    ->schema([
                        TextEntry::make('item.name')->label('Nama barang'),
                        TextEntry::make('kategori'),
                        TextEntry::make('note'),
                    ])->columns(3),
                Section::make('Detail item')
                    ->schema([
                        TextEntry::make('item.harga'),
                        TextEntry::make('item.berat'),
                        TextEntry::make('item.satuan'),
                    ])->columns(3),
                Section::make('Detail pembayaran')
                    ->schema([
                        TextEntry::make('pembayaran.total_harga'),
                        TextEntry::make('pembayaran.fee'),
                        TextEntry::make('pembelian.status'),
                    ])->columns(3),
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'view' => Pages\ViewPembelian::route('/{record}'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
