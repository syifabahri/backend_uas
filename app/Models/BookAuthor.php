<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookAuthor extends Pivot
{
    protected $table = 'book_authors';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'book_id', 'author_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }
}
