<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookAuthorController;

// 🔐 Login dan Register (tanpa token)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 🔐 Semua rute di bawah ini pakai Sanctum token
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // 👤 User
    Route::apiResource('users', UserController::class);
    Route::get('/users-count', [UserController::class, 'count']);

    // 📚 Buku
    Route::apiResource('books', BookController::class);

    // ✍️ Penulis
    Route::apiResource('authors', AuthorController::class);

    // 🔁 Relasi Buku ↔ Penulis (custom karena tidak pakai ID)
    Route::get('/book-authors', [BookAuthorController::class, 'index']);
    Route::post('/book-authors', [BookAuthorController::class, 'store']);
    Route::get('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'show']);
    Route::patch('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'update']);
    Route::delete('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'destroy']);

    // 📥 Peminjaman Buku
    Route::apiResource('loans', LoanController::class);

    // 📊 Statistik Dashboard
    Route::get('/dashboard-counts', function () {
        return response()->json([
            'users' => \App\Models\User::count(),
            'books' => \App\Models\Book::count(),
            'authors' => \App\Models\Author::count(),
            'loans' => \App\Models\Loan::count(),
            'book_authors' => \App\Models\BookAuthor::count(), // Tambahkan penghitungan untuk book_authors
        ]);
    });
});