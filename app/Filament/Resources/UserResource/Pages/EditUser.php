<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    // public function mount($record = null): void
    // {
    //     // Automatically load the authenticated user's data
    //     $user = Filament::auth()->user();
    //     $userId = $user->id;

    //     if (!$record) {
    //         $record = User::find($userId);
    //     }

    //     if ($record) {
    //         parent::mount($record->id);
    //     } else {
    //         abort(404, 'User not found.');
    //     }
    // }
}
