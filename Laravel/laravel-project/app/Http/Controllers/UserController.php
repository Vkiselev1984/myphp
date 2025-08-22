<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function index()
    {
        $users = DB::connection('mysql')->table('user')->select(['id', 'first_name', 'last_name', 'email'])->get();
        return view('users', ['users' => $users]);
    }

    public function indexTwig()
    {
        $users = DB::connection('mysql')->table('user')->select(['id', 'first_name', 'last_name', 'email'])->get();
        return view('users', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        DB::connection('mysql')->table('user')->insert($data);
        return redirect()->route('users.index')->with('status', 'Пользователь добавлен');
    }

    public function destroy($id)
    {
        DB::connection('mysql')->table('user')->where('id', $id)->delete();
        return redirect()->route('users.index')->with('status', 'Пользователь удален');
    }
}
