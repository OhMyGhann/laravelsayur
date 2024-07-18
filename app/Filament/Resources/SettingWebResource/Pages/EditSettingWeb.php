<?php

namespace App\Filament\Resources\SettingWebResource\Pages;

use App\Filament\Resources\SettingWebResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSettingWeb extends EditRecord
{
    protected static string $resource = SettingWebResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
