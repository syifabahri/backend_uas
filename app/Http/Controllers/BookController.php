<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::with('authors')->get();
    }

    public function store(Request $request)
    {
        return Book::create($request->all());
    }

    public function show($id)
    {
        return Book::with('authors')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return $book;
    }

    public function destroy($id)
    {
        return Book::destroy($id);
    }
}
