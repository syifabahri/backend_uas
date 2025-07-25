<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookAuthorController extends Controller
{
    // Tampilkan semua relasi buku-penulis
    public function index()
    {
        $bookAuthors = DB::table('book_authors')
            ->join('books', 'book_authors.book_id', '=', 'books.book_id')
            ->join('authors', 'book_authors.author_id', '=', 'authors.author_id')
            ->select('book_authors.*', 'books.title as book_title', 'authors.name as author_name')
            ->get();

        return response()->json($bookAuthors);
    }

    // Tambahkan relasi buku dengan penulis
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,book_id',
            'author_id' => 'required|exists:authors,author_id',
        ]);

        // Cegah duplikasi
        $exists = DB::table('book_authors')
            ->where('book_id', $validated['book_id'])
            ->where('author_id', $validated['author_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Relation already exists'], 409);
        }

        DB::table('book_authors')->insert([
            'book_id' => $validated['book_id'],
            'author_id' => $validated['author_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Author attached to book'], 201);
    }

    // Tampilkan relasi tertentu
    public function show($book_id, $author_id)
    {
        $relation = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->first();

        if (!$relation) {
            return response()->json(['message' => 'Relation not found'], 404);
        }

        return response()->json($relation);
    }

    // Perbarui relasi buku dan penulis
    public function update(Request $request, $book_id, $author_id)
    {
        $validated = $request->validate([
            'new_book_id' => 'required|exists:books,book_id',
            'new_author_id' => 'required|exists:authors,author_id',
        ]);

        // Cek apakah relasi yang akan diupdate ada
        $existingRelation = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->first();

        if (!$existingRelation) {
            return response()->json(['message' => 'Relation not found'], 404);
        }

        // Cegah duplikasi untuk kombinasi baru
        $exists = DB::table('book_authors')
            ->where('book_id', $validated['new_book_id'])
            ->where('author_id', $validated['new_author_id'])
            ->where('book_id', '!=', $book_id) // Pastikan bukan relasi yang sedang diupdate
            ->where('author_id', '!=', $author_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'New relation already exists'], 409);
        }

        DB::beginTransaction();
        try {
            // Hapus relasi lama
            DB::table('book_authors')
                ->where('book_id', $book_id)
                ->where('author_id', $author_id)
                ->delete();

            // Tambahkan relasi baru
            DB::table('book_authors')->insert([
                'book_id' => $validated['new_book_id'],
                'author_id' => $validated['new_author_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Relation updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update relation: ' . $e->getMessage()], 500);
        }
    }

    // Hapus relasi buku dan penulis
    public function destroy($book_id, $author_id)
    {
        $deleted = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Relation deleted']);
        }

        return response()->json(['message' => 'Relation not found'], 404);
    }
}