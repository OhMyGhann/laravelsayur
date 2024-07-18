<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SettingWeb;
use App\Models\Berita;
use Illuminate\Http\Request;
use App\Models\Order; // Tambahkan ini untuk menggunakan model Order
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = Item::take(20)->get(); // Mengambil 20 data produk
        $banners = Berita::where('tipe', 'banner')->get(); // Mengambil semua data Berita bertipe banner
        $sliders = Berita::where('tipe', 'slider')->get(); // Mengambil semua data Berita bertipe banner
        $settings = SettingWeb::all(); // Mengambil semua data setting website
        $totalOrders = Order::count(); // Menghitung total order
        $totalItems = Item::count(); // Menghitung total item
        return view('layouts.home', compact('items', 'settings', 'user', 'banners', 'sliders', 'totalOrders', 'totalItems'));
    }
}
