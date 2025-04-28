<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Barang extends Model
{

    use HasUlids;
    
    protected $fillable =[
    'nama_barang',
    'jumlah',
    'harga'
    ];

    protected $table = 'barang';

    protected function casts(): array
    {
        return [
            'nama_barang' => 'string',
        ];
    }
}
