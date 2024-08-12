<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'order_number', 'total_price', 'no_hp', 'alamat', 'status', 'shipping_price', 'note', 'buktitf'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor untuk menerjemahkan status ke bahasa Indonesia.
     *
     * @return string
     */
    public function getTranslatedStatusAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Menunggu';
            case 'processing':
                return 'Sedang Diproses';
            case 'packed':
                return 'Sedang Dikemas';
            case 'completed':
                return 'Order Selesai';
            case 'declined':
                return 'Ditolak';
            default:
                return $this->status;
        }
    }
}
