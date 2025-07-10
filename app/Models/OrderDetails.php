<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'id_barang',
        'jumlah',
    ];

    protected function casts(): array
    {
        return [
            'order_id' => 'string',
            'id_barang' => 'string',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
