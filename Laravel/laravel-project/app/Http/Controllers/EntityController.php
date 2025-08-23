<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\DeleteBookRequest;

class EntityController extends Controller
{

    public function view(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $showcase = DB::connection('mysql')
            ->table('books')
            ->select(['id', 'book_name'])
            ->orderByDesc('id')
            ->limit(12)
            ->get();

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

        // Reserved maps for both blocks
        $bookIds = $showcase->pluck('id')->merge($results->pluck('id'))->unique()->values();
        $reservedByAny = [];
        $reservedByMe = [];
        if ($bookIds->isNotEmpty()) {
            $any = DB::connection('mysql')
                ->table('user_books as ub')
                ->join('new_table as nt', 'nt.id', '=', 'ub.id')
                ->whereIn('ub.book_id', $bookIds)
                ->where('nt.reseved', 1)
                ->select('ub.book_id')
                ->groupBy('ub.book_id')
                ->pluck('ub.book_id');
            foreach ($any as $bid) { $reservedByAny[$bid] = true; }

            if (auth()->check()) {
                $mine = DB::connection('mysql')
                    ->table('user_books as ub')
                    ->join('new_table as nt', 'nt.id', '=', 'ub.id')
                    ->where('ub.user_id', auth()->id())
                    ->whereIn('ub.book_id', $bookIds)
                    ->where('nt.reseved', 1)
                    ->pluck('ub.book_id');
                foreach ($mine as $bid) { $reservedByMe[$bid] = true; }
            }
        }

        return view('book', [
            'q' => $q,
            'showcase' => $showcase,
            'results' => $results,
            'reserved_by_any' => $reservedByAny,
            'reserved_by_me' => $reservedByMe,
        ]);
    }

    public function reserve(Request $request, int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $userId = auth()->id();

        $ub = DB::connection('mysql')->table('user_books')
            ->where('user_id', $userId)
            ->where('book_id', $id)
            ->first();

        if (!$ub) {
            $ubId = DB::connection('mysql')->table('user_books')->insertGetId([
                'user_id' => $userId,
                'book_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $ubId = $ub->id;
        }

        DB::connection('mysql')->table('new_table')->updateOrInsert(
            ['id' => $ubId],
            ['reseved' => 1, 'updated_at' => now(), 'created_at' => now()]
        );

        \Log::info('Book reserved', ['book_id' => $id, 'user_id' => $userId]);

        return redirect()->route('books.index')->with('status', 'Книга зарезервирована');
    }

    public function unreserve(Request $request, int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $userId = auth()->id();

        $ub = DB::connection('mysql')->table('user_books')
            ->where('user_id', $userId)
            ->where('book_id', $id)
            ->first();
        if ($ub) {
            DB::connection('mysql')->table('new_table')->where('id', $ub->id)->update([
                'reseved' => 0,
                'updated_at' => now(),
            ]);
            \Log::info('Book unreserved', ['book_id' => $id, 'user_id' => $userId]);
        }

        return redirect()->route('books.index')->with('status', 'Резерв снят');
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
