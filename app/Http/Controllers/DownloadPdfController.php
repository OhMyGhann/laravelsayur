<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SettingWeb;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadPdfController extends Controller
{
    public function download(Order $record)
    {
        $client = new Party([
            'name'          => 'Sayourku',
            'phone'         => '+62 318-9486',
            'custom_fields' => [
                'business id' => '365#GG',
            ],
        ]);

        $setting = SettingWeb::find(1);
        $orderItems = OrderItem::where('order_id', $record->id)->get();
        $user = User::where('id', Auth::id())->first();

        $customer = new Party([
            'name' => $user->name,
            'address' => $user->address,
        ]);

        $notes = $record->note;

        $items = [];
        foreach ($orderItems as $orderItem) {
            $items[] = InvoiceItem::make($orderItem->item->name)
                ->description($orderItem->item->note)
                ->pricePerUnit($orderItem->sub_total / $orderItem->quantity)
                ->quantity($orderItem->quantity);
        }

        $invoice = Invoice::make()

            ->buyer($customer)
            ->seller($client)
            ->sequence(667)
            ->serialNumberFormat('{SERIES}/{SEQUENCE}')
            ->shipping($record->shipping_price)
            ->currencySymbol('Rp.')
            ->currencyCode('IDR')
            ->notes($notes)
            ->filename($client->name . ' ' . $customer->name)
            ->logo(public_path('storage/' . $setting->logo_1))
            ->currencyFormat('{SYMBOL}{VALUE}');


        foreach ($items as $item) {
            $invoice->addItem($item);
        }

        return $invoice->stream();
    }
}
