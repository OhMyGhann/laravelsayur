<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Berita;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BeritaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeritaResource\RelationManagers;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $pluralModelLabel = 'Berita';

    protected static ?string $navigationLabel = 'Berita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipe')
                    ->label('Tipe')
                    ->options([
                        'banner' => 'Banner',
                        'slider' => 'Slider',
                        'post' => 'Post',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('item_id')
                    ->label('Product')
                    ->options(Item::query()->where('stok', '>', 0)->pluck('name', 'id'))
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sub_title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255)
                    ->required(),
                FileUpload::make('image_path')
                    ->label('Gambar')
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->size(150),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Jenis berita')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul'),
                Tables\Columns\TextColumn::make('sub_title')
                    ->label('Sub judul'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'view' => Pages\ViewBerita::route('/{record}'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
