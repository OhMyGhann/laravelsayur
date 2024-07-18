<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Item;
use Filament\Actions;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {

        $items = $this->data['items'];

        foreach ($items as $item) {
            $item_id = $item['item_id'];
            $quantity = $item['quantity'];

            $product = Item::find($item_id);
            if ($product) {
                $product->stok -= $quantity;
                $product->save();
            }
        }
    }
}
