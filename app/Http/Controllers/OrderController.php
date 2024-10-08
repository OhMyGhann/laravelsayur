<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\KeranjangBelanja;
use App\Models\SettingWeb;

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
    
        $request->validate([
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);
    
        $order = new Order();
        $order->user_id = $user->id;
        $order->order_number = 'SY-' . random_int(100000, 9999999);
        $order->total_price = $cartItems->sum(function ($cartItem) {
            return $cartItem->item->harga * $cartItem->quantity;
        });
        $order->status = 'pending';
        $order->no_hp = $request->no_hp;
        $order->alamat = $request->alamat;
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
    

    public function uploadPaymentProof(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
    
        if ($request->hasFile('bukti_tf')) {
            $path = $request->file('bukti_tf')->store('bukti_tf', 'public');
            $order->bukti_tf = $path;
            $order->save();
    
            return redirect()->route('order.index')->with('success', 'Bukti pembayaran berhasil diunggah.');
        }
    
        return redirect()->route('order.index')->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
    


    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        return redirect()->route('order.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
