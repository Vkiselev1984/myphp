<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Витрина: последние 12 книг
        $showcase = DB::connection('mysql')
            ->table('books')
            ->select(['id', 'book_name'])
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        // Результаты поиска (если задан q)
        $results = collect();
        if ($q !== '') {
            $results = DB::connection('mysql')
                ->table('books')
                ->select(['id', 'book_name'])
                ->where('book_name', 'like', "%$q%")
                ->orderBy('book_name')
                ->limit(50)
                ->get();
        }

        return view('home', [
            'q' => $q,
            'results' => $results,
            'showcase' => $showcase,
        ]);
    }
}
