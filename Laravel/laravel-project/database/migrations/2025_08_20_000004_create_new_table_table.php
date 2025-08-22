<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_table', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // совпадает с id в user_books
            $table->boolean('reseved')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('id')->references('id')->on('user_books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('new_table');
    }
};
