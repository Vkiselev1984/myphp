<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservedController extends Controller
{
    public function index(Request $request)
    {
        // Единая схема: таблица users (Breeze)
        $users = DB::connection('mysql')
            ->table('users')
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $userId = (int) $request->query('user_id', 0);

        $rows = collect();
        if ($userId > 0) {
            $rows = DB::connection('mysql')
                ->table('user_books as ub')
                ->join('users as u', 'u.id', '=', 'ub.user_id')
                ->join('books as b', 'b.id', '=', 'ub.book_id')
                ->leftJoin('new_table as nt', 'nt.id', '=', 'ub.id')
                ->where('ub.user_id', $userId)
                ->select([
                    DB::raw('u.name as name'),
                    'b.book_name',
                    DB::raw('COALESCE(nt.reseved, 0) as reseved'),
                ])
                ->orderBy('b.book_name')
                ->get();
        }

        return view('reserved', [
            'users' => $users,
            'rows' => $rows,
            'user_id' => $userId,
        ]);
    }

    public function my(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $books = DB::connection('mysql')
            ->table('user_books as ub')
            ->join('books as b', 'b.id', '=', 'ub.book_id')
            ->where('ub.user_id', $userId)
            ->orderByDesc('b.id')
            ->select(['b.id', 'b.book_name'])
            ->get();

        return view('my_reserved', [
            'books' => $books,
        ]);
    }
}
