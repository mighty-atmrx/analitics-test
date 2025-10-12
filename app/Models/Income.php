<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    protected $table = 'incomes';
    protected $guarded = [];

    protected $casts = [
        'income_id' => 'integer',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'decimal:4',
        'nm_id' => 'integer',
        'date' => 'date',
        'last_change_date' => 'date',
        'date_close' => 'date',
    ];

    protected $fillable = [
        'account_id', 'income_id', 'number', 'date', 'last_change_date', 'supplier_article',
        'tech_size', 'barcode', 'quantity', 'total_price', 'date_close',
        'warehouse_name', 'nm_id', 'sync_date'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
