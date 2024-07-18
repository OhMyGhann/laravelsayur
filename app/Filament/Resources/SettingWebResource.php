<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SettingWeb;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SettingWebResource\Pages;
use App\Filament\Resources\SettingWebResource\RelationManagers;

class SettingWebResource extends Resource
{
    protected static ?string $model = SettingWeb::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo')
                    ->schema([
                        FileUpload::make('logo_1')
                            ->label('Main Logo'),
                        FileUpload::make('logo_2')
                            ->label('Second Logo'),
                        FileUpload::make('logo_3')
                            ->label('Title Logo'),
                    ])->columns(3),
                Forms\Components\Section::make('Warna')
                    ->schema([
                        Forms\Components\ColorPicker::make('warna_1'),
                        Forms\Components\ColorPicker::make('warna_2'),
                        Forms\Components\ColorPicker::make('warna_3'),
                    ])->columns(3),
                Section::make('Web information')
                    ->schema([
                        TextInput::make('phone')
                            ->numeric(),
                        TextInput::make('social_media.instagram'),
                        TextInput::make('deskripsi_web')
                            ->columnSpan('full')
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_1')
                    ->label('Logo 1')
                    ->size(150),
                ImageColumn::make('logo_2')
                    ->label('Logo 2')
                    ->size(150),
                ImageColumn::make('logo_3')
                    ->label('Logo 3')
                    ->size(150),
                Tables\Columns\TextColumn::make('warna_1'),
                Tables\Columns\TextColumn::make('warna_2'),
                Tables\Columns\TextColumn::make('warna_3'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListSettingWebs::route('/'),
            'create' => Pages\CreateSettingWeb::route('/create'),
            'edit' => Pages\EditSettingWeb::route('/{record}/edit'),
        ];
    }
}
