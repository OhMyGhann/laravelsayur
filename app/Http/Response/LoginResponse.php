<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $panelId = Filament::getCurrentPanel()->getId();

        if ($panelId === 'app') {
            return redirect('/');
        }
    }
}
