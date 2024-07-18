<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingWeb;
use App\Models\Item;
use App\Models\KeranjangBelanja;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat pesanan Anda.');
        }

        $settings = SettingWeb::all(); // Mengambil semua data setting website
        $cartItems = KeranjangBelanja::where('user_id', Auth::id())->with('item')->get();

        $cart = $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->item_id,
                'name' => $cartItem->item->name,
                'price' => $cartItem->item->harga,
                'quantity' => $cartItem->quantity,
                'image_path' => $cartItem->item->image_path,
            ];
        });

        return view('layouts.shop.cart', ['cart' => $cart, 'settings' => $settings]);
    }

    public function addToCart(Request $request)
    {
        // Cari item dalam database
        $itemModel = Item::find($request->id);
        if (!$itemModel) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        $quantity = $request->quantity;

        // Simpan ke database
        $cartItem = KeranjangBelanja::updateOrCreate(
            ['user_id' => Auth::id(), 'item_id' => $itemModel->id],
            ['quantity' => \DB::raw("quantity + $quantity")]
        );

        $cartCount = KeranjangBelanja::where('user_id', Auth::id())->count();

        return response()->json(['success' => true, 'cartCount' => $cartCount]);
    }

    public function updateCartQuantity(Request $request)
    {
        $cartItem = KeranjangBelanja::where('user_id', Auth::id())
            ->where('item_id', $request->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->save();
            }

            $cartCount = KeranjangBelanja::where('user_id', Auth::id())->count();
            $total = $this->calculateTotal(Auth::id());

            return response()->json(['success' => true, 'cartCount' => $cartCount, 'total' => $total]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

    public function removeFromCart(Request $request)
    {
        $cartItem = KeranjangBelanja::where('user_id', Auth::id())
            ->where('item_id', $request->id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();

            $cartCount = KeranjangBelanja::where('user_id', Auth::id())->count();
            $total = $this->calculateTotal(Auth::id());

            return response()->json(['success' => true, 'cartCount' => $cartCount, 'total' => $total]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

    private function calculateTotal($userId)
    {
        $cartItems = KeranjangBelanja::where('user_id', $userId)->with('item')->get();

        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->item->harga * $cartItem->quantity;
        });
        $shipping = 30000;
        $total = $subtotal + $shipping;

        return [
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.')
        ];
    }
}
