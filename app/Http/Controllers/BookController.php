<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    private $service;

    /**
     * BookController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JSONAPICollection
     */
    public function index()
    {
        return $this->service->fetchResourcesIndex(Book::class, "books");
    }

    /**
     * @param JSONAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(JSONAPIRequest $request)
    {
        return $this->service->createResource(Book::class, $request->input("data.attributes"));
    }

    /**
     * @param int $book
     * @return JSONAPIResource
     */
    public function show(int $book)
    {
        return $this->service->fetchResource(Book::class, $book, 'books');
    }

    /**
     * @param JSONAPIRequest $request
     * @param Book $book
     * @return JSONAPIResource
     */
    public function update(JSONAPIRequest $request, Book $book)
    {
        return $this->service->updateResource($book, $request->input('data.attributes'));
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
