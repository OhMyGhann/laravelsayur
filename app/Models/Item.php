<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'name',
        'kategori',
        'harga',
        'berat',
        'satuan',
        'stok',
        'note',
    ];

    public function shoppingCarts()
    {
        return $this->hasMany(KeranjangBelanja::class);
    }
}
