<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUlids;

    protected $table = 'order';

    protected $fillable = [
        'customer_id',
        'order_date',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'string',
          ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
{
    return $this->hasMany(OrderDetails::class, 'order_id');
}

}
