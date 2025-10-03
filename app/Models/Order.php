<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

    protected $casts = [
        'barcode' => 'integer',
        'total_price' => 'decimal:2',
        'discount_percent' => 'integer',
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'is_cancel' => 'boolean',
        'date' => 'datetime',
        'last_change_date' => 'date',
        'cancel_dt' => 'date',
    ];

    protected $fillable = [
        'g_number', 'date', 'last_change_date', 'supplier_article', 'tech_size',
        'barcode', 'total_price', 'discount_percent', 'warehouse_name', 'oblast',
        'income_id', 'odid', 'nm_id', 'subject', 'category', 'brand', 'is_cancel', 'cancel_dt',
    ];
}
