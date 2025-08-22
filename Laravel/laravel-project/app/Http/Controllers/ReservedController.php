<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservedController extends Controller
{
    public function index(Request $request)
    {
        $users = DB::connection('mysql')
            ->table('user')
            ->select(['id', 'first_name', 'last_name'])
            ->orderBy('first_name')
            ->get();

        $userId = (int) $request->query('user_id', 0);

        $rows = collect();
        if ($userId > 0) {
            $db = env('DB_DATABASE', 'laravel');
            $rows = DB::connection('mysql')
                ->table("$db.user_books as ub")
                ->join("$db.user as u", 'u.id', '=', 'ub.user_id')
                ->join("$db.books as b", 'b.id', '=', 'ub.book_id')
                ->leftJoin("$db.new_table as nt", 'nt.id', '=', 'ub.id')
                ->where('ub.user_id', $userId)
                ->select([
                    'u.first_name',
                    'u.last_name',
                    'b.book_name',
                    DB::raw('COALESCE(nt.reseved, 1) as reseved'),
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
}
