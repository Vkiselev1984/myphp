<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);


Route::middleware('auth')->get('/users', [\App\Http\Controllers\UsersController::class, 'index'])->name('users.index');


Route::get('/simple-test', [\App\Http\Controllers\SimpleController::class, 'test']);

Route::get('/books', [\App\Http\Controllers\EntityController::class, 'view'])->name('books.index');
Route::post('/books', [\App\Http\Controllers\EntityController::class, 'store'])->name('books.store');
Route::post('/books/{id}/reserve', [\App\Http\Controllers\EntityController::class, 'reserve'])->middleware('auth')->name('books.reserve');
Route::post('/books/{id}/unreserve', [\App\Http\Controllers\EntityController::class, 'unreserve'])->middleware('auth')->name('books.unreserve');
Route::delete('/books/{id}', [\App\Http\Controllers\EntityController::class, 'destroy'])->whereNumber('id')->name('books.destroy');

Route::get('/news/create-test', function () {
    $news = \App\Models\News::create([
        'title' => 'Test news title',
        'body' => 'Test news body',
        'hidden' => false,
    ]);
    return response('Created news id: ' . $news->id);
});

Route::get('/news/{id}/hide', function ($id) {
    $news = \App\Models\News::findOrFail($id);
    $news->hidden = true;
    $news->save();

    \App\Events\NewsHidden::dispatch($news);

    return response('Hidden news id: ' . $news->id);
});

Route::middleware([\App\Http\Middleware\DataLogger::class])->group(function () {
    Route::get('/reserved', [\App\Http\Controllers\ReservedController::class, 'index'])->name('reserved.index');
    Route::middleware('auth')->get('/my/reserved', [\App\Http\Controllers\ReservedController::class, 'my'])->name('reserved.my');
    Route::get('/db-introspect', [\App\Http\Controllers\DbIntrospectController::class, 'index'])->name('db.introspect');

    Route::get('/logs', function () {
        return view('logs');
    })->name('logs.index');

    Route::get('/api/logs', function (\Illuminate\Http\Request $request) {
        $limit = (int) $request->query('limit', 50);
        $items = \Illuminate\Support\Facades\DB::connection('mysql')
            ->table('logs')
            ->orderByDesc('id')
            ->limit($limit > 0 && $limit <= 200 ? $limit : 50)
            ->get();
        return response()->json($items);
    })->name('logs.api');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

require __DIR__ . '/auth.php';
