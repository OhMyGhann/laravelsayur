<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\KeranjangBelanja;
use App\Models\SettingWeb;
// use App\Models\Item;

class OrderController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat pesanan Anda.');
        }

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();
        $settings = SettingWeb::all();

        return view('layouts.shop.order', compact('orders', 'settings', 'user'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk membuat pesanan.');
        }

        $user = Auth::user();
        $cartItems = KeranjangBelanja::where('user_id', $user->id)->with('item')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong.');
        }

        // // Validasi pembayaran
        // if (!$request->has('payment_proof') || !$request->file('payment_proof')->isValid()) {
        //     return redirect()->route('cart')->with('error', 'Bukti pembayaran tidak valid.');
        // }

        // // Simpan bukti pembayaran
        // $paymentProofPath = $request->file('payment_proof')->store('public/payment_proofs');

        $order = new Order();
        $order->user_id = $user->id;
        $order->order_number = 'SY-' . random_int(100000, 9999999);
        $order->total_price = $cartItems->sum(function ($cartItem) {
            return $cartItem->item->harga * $cartItem->quantity;
        }) + 30000; // Include shipping cost
        $order->status = 'proses';
        // $order->payment_proof = $paymentProofPath; // Simpan path bukti pembayaran
        $order->save();

        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->item_id = $cartItem->item_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->unit_price = $cartItem->item->harga;
            $orderItem->sub_total = $cartItem->item->harga * $cartItem->quantity;
            $orderItem->save();
        }

        // Clear the cart
        KeranjangBelanja::where('user_id', $user->id)->delete();

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil ditempatkan.');
    }
}