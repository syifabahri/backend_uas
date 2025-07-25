<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'book_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'book_id', 'title', 'isbn', 'publisher', 'year_published', 'stock',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id', 'book_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }
}
