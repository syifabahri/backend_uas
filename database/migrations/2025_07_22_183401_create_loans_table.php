<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('loans', function (Blueprint $table) {
        $table->string('loan_id')->primary();
        $table->string('user_id');
        $table->string('book_id');
        $table->timestamps();

        // Foreign Keys
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
