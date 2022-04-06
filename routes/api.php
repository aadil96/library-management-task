<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AcceptJson;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# Auth
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])
    ->middleware('auth:api')
    ->name('logout');

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::middleware(['auth:api', AcceptJson::class])->group(function () {
    # Users
    Route::get('auth/profile', [UserController::class, 'show'])->name('auth.show');
    Route::put('auth/profile', [UserController::class, 'update'])->name('auth.update');

    Route::get('auth/rent/books/{book}', [UserController::class, 'rent']);
    Route::get('auth/books', [UserController::class, 'books']);
    Route::get('auth/books/{book}/rent', [UserController::class, 'rent'])->name('auth.books.rent');
    Route::get('auth/books/{book}/return', [UserController::class, 'return'])->name('auth.books.return');

    # Books
    Route::resource('books', BookController::class)
        ->only('index', 'show', 'store', 'update', 'destroy');
});
