<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metode;
use App\Models\Order;
use App\Models\SettingWeb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function showPaymentPage($order_id)
    {
        $user = Auth::user();
        $metodes = Metode::all();
        $settings = SettingWeb::all(); // Mengambil semua data setting website
        $order = Order::find($order_id); // Mengambil detail order
    
        return view('layouts.shop.payment', compact('metodes', 'order_id', 'settings', 'order'));
    }
    

    public function processPayment(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'bukti_tf' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $order_id = $request->order_id;
        $metode_id = $request->metode_id;
        $bukti_tf = $request->file('bukti_tf')->store('bukti_tf', 'public');

        $order = Order::find($order_id);
        if($order && $order->status == 'processing') {
            // $order->status = 'processing'; // Atau status lain yang sesuai
            $order->bukti_tf = $bukti_tf;
            $order->save();

            return redirect()->route('order.index')->with('success', 'Payment is being processed.');
        }

        return redirect()->route('order.index')->with('error', 'Invalid order or order is not pending.');
    }
}
