<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Manajemen barang';

    protected static ?string $navigationLabel = 'Daftar Barang';

    protected static ?string $pluralModelLabel = 'Daftar barang';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama barang')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kategori')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('note')
                            ->label('catatan')
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Section::make('Detail Item')
                    ->schema([
                        Forms\Components\TextInput::make('harga')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('berat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('satuan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make('Gambar')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Gambar')
                            ->required(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama barang'),
                Tables\Columns\TextColumn::make('kategori')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->size(150),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Item')
                    ->schema([
                        TextEntry::make('name')->label('Nama item'),
                        TextEntry::make('kategori'),
                        TextEntry::make('note'),
                    ])->columns(3),
                Section::make('Detail item')
                    ->schema([
                        TextEntry::make('harga'),
                        TextEntry::make('berat'),
                        TextEntry::make('satuan'),
                        TextEntry::make('stok'),
                    ])->columns(2),
                Section::make('Gambar')
                    ->schema([
                        ImageEntry::make('image_path')
                            ->label('Gambar'),
                    ])->columns(1),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            // 'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
