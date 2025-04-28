<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Customer extends Model
{
    use HasUlids;
    
    protected $table = 'customer';
    
    protected $fillable = [
        'customer_name',
        'alamat',
        'no_hp',
    ];

    protected function casts(): array
    {
        return [
            'customer_name' => 'string',
            'no_hp' => 'string',
        ];
    }

}
