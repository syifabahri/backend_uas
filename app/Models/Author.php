<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $primaryKey = 'author_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'author_id', 'name', 'nationality', 'birthdate',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_authors', 'author_id', 'book_id');
    }
}
