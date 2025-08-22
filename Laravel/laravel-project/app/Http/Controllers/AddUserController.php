<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddUserController extends Controller
{
    public function index()
    {
        return view('add_user');
    }

    public function store(Request $request)
    {

        Log::info('AddUserController@store hit', $request->only(['first_name', 'last_name', 'email']));

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
        ]);

        $ok = DB::connection('mysql')->table('user')->insert([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ]);

        Log::info('AddUserController@store insert result', ['ok' => $ok]);

        return redirect('/users')->with('status', $ok ? 'Пользователь успешно добавлен' : 'Не удалось добавить пользователя');
    }
}
