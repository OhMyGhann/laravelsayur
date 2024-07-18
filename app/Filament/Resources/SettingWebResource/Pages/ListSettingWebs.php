<?php

namespace App\Filament\Resources\SettingWebResource\Pages;

use App\Filament\Resources\SettingWebResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettingWebs extends ListRecords
{
    protected static string $resource = SettingWebResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
