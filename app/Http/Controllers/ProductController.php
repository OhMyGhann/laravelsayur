<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SettingWeb;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id);
        $settings = SettingWeb::all(); // Mengambil semua data setting website
        return view('layouts.shop.shop-detail', compact('item', 'settings'));
    }
}
