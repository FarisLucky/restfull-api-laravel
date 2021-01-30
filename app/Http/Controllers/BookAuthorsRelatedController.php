<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorsCollection;
use App\Http\Resources\JSONAPICollection;
use App\Models\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class BookAuthorsRelatedController extends Controller
{

    private $service;

    /**
     * BookAuthorsRelatedController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }


    /**
     * @param Book $book
     * @return JSONAPICollection
     */
    public function index(Book $book)
    {
        return $this->service->fetchRelated($book, 'authors');
    }
}
