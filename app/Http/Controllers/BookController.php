<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response | BooksCollection
     */
    public function index()
    {
        $book = QueryBuilder::for(Book::class)->allowedSorts([
            'title',
            'publication_year',
            'created_at',
            'updated_at'
        ])->get();
        return new BooksCollection($book);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store books with resource
     *
     * @param CreateBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBookRequest $request)
    {
        $book = Book::create([
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'publication_year' => $request->input('data.attributes.publication_year'),
        ]);

        return (new BooksResource($book))
            ->response()
            ->header('Location', route(
                'books.show',['book' => $book]
            ));
    }

    /**
     * @param int $book
     * @return BooksResource|int
     */
    public function show(int $book)
    {
        $query = QueryBuilder::for(Book::where("id", $book))
            ->allowedIncludes("authors")
            ->firstOrFail();
        return new BooksResource($query);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return BooksResource
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->input('data.attributes'));
        return new BooksResource($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
