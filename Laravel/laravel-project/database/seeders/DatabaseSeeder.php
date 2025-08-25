<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Breeze users (table: users)
        DB::table('users')->insertOrIgnore([
            ['id' => 1, 'name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'is_admin' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Regular User', 'email' => 'user@example.com', 'password' => Hash::make('password'), 'is_admin' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Books
        DB::table('books')->insertOrIgnore([
            ['id' => 1, 'book_name' => 'Book One', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'book_name' => 'Book Two', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'book_name' => 'Book Three', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'book_name' => 'Book Four', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'book_name' => 'Book Five', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Relations user_books (only some booked, others free)
        DB::table('user_books')->insertOrIgnore([
            ['id' => 1, 'user_id' => 1, 'book_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 2, 'book_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'user_id' => 1, 'book_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Reserve statuses (only id 1 and 3 are reserved; id=2 not reserved)
        DB::table('new_table')->insertOrIgnore([
            ['id' => 1, 'reseved' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'reseved' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Products
        \App\Models\Product::factory()->count(25)->create();
    }
}
