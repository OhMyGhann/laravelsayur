<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {

        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/');
    }
}
