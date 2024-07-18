<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metode extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'bank_name',
        'bank_code',
        'no_rekening',
        'fee_bank',
        'note'
    ];

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
