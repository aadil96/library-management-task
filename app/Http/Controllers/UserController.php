<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Lib\ApiCode;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() : JsonResponse
    {
        return respond(ApiCode::OK, User::with('booksRented')->all());
    }

    public function show() : JsonResponse
    {
        $user = User::/*with('booksRented')->*/where('id', Auth::id())->first();
        return respond(ApiCode::OK, $user);
    }

    public function update(UpdateUserRequest $request) : JsonResponse
    {
        $user = Auth::user();

        $request->merge([
            // 'password' => bcrypt('password'),/
            'gender' => strtolower($request->gender),
        ]);

        $user->update($request->except('password'));

        return respond(ApiCode::SUCCESS);
    }

    public function destroy(User $user) : JsonResponse
    {
        $user->delete();

        return respond(ApiCode::SUCCESS);
    }

    public function books() : JsonResponse
    {
        $booksRented = Auth::user()->booksRented;
        $sanitizedData = collect();
        foreach ($booksRented as $data) {
            $sanitizedData->push([
                "name" =>  $data->name,
                "author" =>  $data->author,
                "cover_image" =>  $data->cover_image,
                "rented_on" =>  $data->pivot->rented_on,
                "rent_due_on" =>  $data->pivot->rent_due_on,
                'return_url' => asset(route('auth.books.return', $data->id))
            ]);
        }


        return respond(ApiCode::OK, $sanitizedData);
    }

    public function rent(Book $book) : JsonResponse
    {
        $transactionExists = Transaction::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('returned_on', null)
            ->exists();

        if ($transactionExists) {
            return respondWithMessage('Book already rented');
        }

        Transaction::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'rented_on' => now(),
            "rent_due_on" => now()->addMonth(),
        ]);

        return respond(ApiCode::SUCCESS);
    }

    public function return(Book $book) : JsonResponse
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->firstOrFail();

        if ($transaction->returned_on === null) {
            $transaction->update(["returned_on" => now()]);
        }

        return respond(ApiCode::SUCCESS);
    }
}
