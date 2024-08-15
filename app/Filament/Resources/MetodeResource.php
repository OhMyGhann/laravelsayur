<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Metode;
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
use App\Filament\Resources\MetodeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MetodeResource\RelationManagers;

class MetodeResource extends Resource
{
    protected static ?string $model = Metode::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Metode Pembayaran';

    protected static ?string $pluralModelLabel = 'Daftar bank';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Bank')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Nama bank')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('bank_code')
                            ->label('Kode bank')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_rekening')
                            ->label('No rekening')
                            ->maxLength(255),
                        // Forms\Components\TextInput::make('fee_bank')
                        //     ->label('Biaya admin')
                        //     ->required()
                        //     ->maxLength(255),
                        Forms\Components\TextInput::make('note')
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
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Nama bank'),
                Tables\Columns\TextColumn::make('bank_code')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fee_bank')
                    ->money('IDR')
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
                        TextEntry::make('bank_name')->label('Nama bank'),
                        TextEntry::make('bank_code')->label('Kode bank'),
                        TextEntry::make('fee_bank'),
                        TextEntry::make('no_rekening'),
                        TextEntry::make('note')->columnSpan(2),
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
            'index' => Pages\ListMetodes::route('/'),
            'create' => Pages\CreateMetode::route('/create'),
            // 'view' => Pages\ViewMetode::route('/{record}'),
            'edit' => Pages\EditMetode::route('/{record}/edit'),
        ];
    }
}
