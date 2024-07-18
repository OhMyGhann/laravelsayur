<?php

// app/Http/Controllers/ShopController.php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SettingWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = Item::select('kategori')->distinct()->get();
        $selectedCategory = $request->input('kategori');
        $search = $request->input('search');

        $query = Item::query();

        if ($selectedCategory) {
            $query->where('kategori', $selectedCategory);
        }

        if ($search) {
            $query->where('name',  'like', '%' . $search . '%');
        }

        $items = $query->paginate(9);

        $settings = SettingWeb::all(); // Mengambil semua data setting website
        return view('layouts.shop.shop', compact('items', 'categories', 'settings', 'user', 'selectedCategory'));
    }
}
