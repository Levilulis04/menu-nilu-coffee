<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_number',
        'queue_number',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
