<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_books', function (Blueprint $table) {
            // Drop old FK to table `user`
            try {
                $table->dropForeign('user_books_user_id_foreign');
            } catch (\Throwable $e) {
                // fallback if name differs
                try { $table->dropForeign(['user_id']); } catch (\Throwable $e2) {}
            }
            // Create FK to `users`
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('user_books', function (Blueprint $table) {
            // Revert to FK to legacy `user` table
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {}
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }
};
