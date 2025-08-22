<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);

Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

Route::get('/test', [\App\Http\Controllers\SimpleController::class, 'test']);

Route::get('/user', [\App\Http\Controllers\AddUserController::class, 'index']);

Route::post('/register', [\App\Http\Controllers\AddUserController::class, 'store'])->name('register');

Route::get('/books', [\App\Http\Controllers\EntityController::class, 'view'])->name('books.index');
Route::post('/books', [\App\Http\Controllers\EntityController::class, 'store'])->name('books.store');
Route::delete('/books/{id}', [\App\Http\Controllers\EntityController::class, 'destroy'])->whereNumber('id')->name('books.destroy');

Route::get('/reserved', [\App\Http\Controllers\ReservedController::class, 'index'])->name('reserved.index');

Route::get('/db-introspect', [\App\Http\Controllers\DbIntrospectController::class, 'index'])->name('db.introspect');
