<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'discount',
        'tax',
    ];

    /**
     * Transaksi dimiliki oleh satu User (kasir).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaksi memiliki banyak OrderItem.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}