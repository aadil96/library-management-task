<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Lib\ApiCode;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        return respond(ApiCode::OK, ['books' => Book::all()]);
    }

    public function show(Book $book)
    {
        return respond(ApiCode::OK, ['books' => $book]);
    }

    public function store(CreateBookRequest $request)
    {
        DB::transaction(function () use ($request) {
            $path = Storage::disk('public')
                ->putFile('', $request->file('cover_image'));

                // Storage::disk('public')->setVisibility($path, 'public');

            Book::create(
                $request->except('cover_image') +
                ['cover_image' => asset('storage/'.$path)]
            );
        });

        return respond(ApiCode::SUCCESS);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        DB::transaction(function () use ($request, $book) {

            $pathOfPreviousCoverImage = explode(asset('storage'), $book->cover_image)[1];
            Storage::disk('public')
                ->delete($pathOfPreviousCoverImage); // Deletes previoulsy uploaded image

                $path = Storage::disk('public')
                ->putFile('', $request->file('cover_image'));

            $book->update(
                $request->except('cover_image') +
                ['cover_image' => asset('storage/'.$path)]
            );
        });

        return respond(ApiCode::SUCCESS);
    }

    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            $pathOfPreviousCoverImage = ltrim(explode(asset('storage'), $book->cover_image)[1], '/');
            Storage::disK('public')->delete($pathOfPreviousCoverImage); // Delete file

            $book->delete();
        });

        return respond(ApiCode::SUCCESS);
    }
}
