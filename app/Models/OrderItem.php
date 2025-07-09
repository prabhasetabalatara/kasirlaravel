<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', // Pastikan ini sesuai dengan migrasi
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * PERUBAHAN: Relasi ke model Transaction.
     * OrderItem dimiliki oleh satu Transaction.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * OrderItem merujuk ke satu Produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}