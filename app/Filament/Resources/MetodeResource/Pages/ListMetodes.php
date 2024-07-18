<?php

namespace App\Filament\Resources\MetodeResource\Pages;

use App\Filament\Resources\MetodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetodes extends ListRecords
{
    protected static string $resource = MetodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
