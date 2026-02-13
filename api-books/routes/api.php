<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;

Route::post('/register', [UserController::class, 'register'])->name('auth.register');
Route::post('/login', [UserController::class, 'login'])->middleware('throttle:10,1')->name('auth.login');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');

    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::patch('/books/{book}', [BookController::class, 'update'])->name('books.patch');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});
