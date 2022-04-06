<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:api'])->group(function () {
    # Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    // Route::get('users/{user}')

    # Books
    Route::resource('books', BookController::class)
        ->only('index', 'show', 'store', 'update', 'destroy');
});
