<?php

namespace App\Filament\Resources\MetodeResource\Pages;

use App\Filament\Resources\MetodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetode extends EditRecord
{
    protected static string $resource = MetodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
