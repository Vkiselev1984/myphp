<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Пользователи
        DB::connection('mysql')->table('user')->insertOrIgnore([
            ['id' => 1, 'first_name' => 'Ivan', 'last_name' => 'Ivanov', 'email' => 'ivan@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'first_name' => 'Petr', 'last_name' => 'Petrov', 'email' => 'petr@example.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Книги
        DB::connection('mysql')->table('books')->insertOrIgnore([
            ['id' => 1, 'book_name' => 'Book One', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'book_name' => 'Book Two', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Связи пользователь-книга
        DB::connection('mysql')->table('user_books')->insertOrIgnore([
            ['id' => 1, 'user_id' => 1, 'book_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 2, 'book_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'user_id' => 1, 'book_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Статусы резервирования
        DB::connection('mysql')->table('new_table')->insertOrIgnore([
            ['id' => 1, 'reseved' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'reseved' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'reseved' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
