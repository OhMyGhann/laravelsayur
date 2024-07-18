<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount($record = null): void
    {
        $user = Filament::auth()->user();
        $userId = $user->id;

        if (!$record) {
            $record = User::find($userId);
        }

        if ($record) {
            parent::mount($record->id);
        } else {
            abort(404, 'User not found.');
        }
    }
}
