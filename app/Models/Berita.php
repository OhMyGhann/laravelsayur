<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'tipe',
        'title',
        'sub_title',
        'item_id',
        'note'
    ];


    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
