<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function __invoke()
    {
        DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
        DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
        DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
        DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
        DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
        $users = DB::connection('mysql')->table('user')->select(['id', 'first_name', 'last_name', 'email'])->get();
        return view('user', ['users' => $users]);

    }
}
