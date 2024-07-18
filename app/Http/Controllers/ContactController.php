<?php

namespace App\Http\Controllers;

// use App\Models\Item;
use App\Models\SettingWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // $items = Item::all(); // Mengambil semua data produk
        $settings = SettingWeb::all(); // Mengambil semua data setting website
        return view('layouts.shop.contact', compact('settings', 'user'));
    }
}
