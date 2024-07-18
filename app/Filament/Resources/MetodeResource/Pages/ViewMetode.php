<?php

namespace App\Filament\Resources\MetodeResource\Pages;

use App\Filament\Resources\MetodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMetode extends ViewRecord
{
    protected static string $resource = MetodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
