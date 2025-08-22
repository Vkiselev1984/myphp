<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\DeleteBookRequest;

class EntityController extends Controller
{

    public function view(\Illuminate\Http\Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Витрина: последние 12 книг
        $showcase = DB::connection('mysql')
            ->table('books')
            ->select(['id', 'book_name'])
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        // Результаты поиска
        $results = collect();
        if ($q !== '') {
            $results = DB::connection('mysql')
                ->table('books')
                ->select(['id', 'book_name'])
                ->where('book_name', 'like', "%$q%")
                ->orderBy('book_name')
                ->limit(100)
                ->get();
        }

        return view('book', [
            'q' => $q,
            'showcase' => $showcase,
            'results' => $results,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_name' => 'required|string|max:255',
        ]);

        DB::connection('mysql')->table('books')->insert([
            'book_name' => $data['book_name'],
        ]);

        return redirect()->route('books.index')->with('status', 'Книга добавлена');
    }

    public function destroy(DeleteBookRequest $request, int $id)
    {
        $deleted = DB::connection('mysql')->table('books')->where('id', $id)->delete();

        if ($deleted === 0) {
            return redirect()->route('books.index')->with('status', 'Книга уже удалена или не найдена');
        }

        return redirect()->route('books.index')->with('status', 'Книга удалена');
    }
}
