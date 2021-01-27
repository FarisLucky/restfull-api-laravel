<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookAuthorsRelatedController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BooksAuthorsRelationshipsController;
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

Route::middleware(['auth:api'])
    ->prefix('v1')
    ->group(function () {

        /**
         * Authors Route
         * Routing ini digunakan untuk api dari Authors Controller
         * @param AuthorController
         */
        Route::apiResource('authors',AuthorController::class)->names('authors');

        /**
         * Books Route
         * Routing ini digunakan untuk api dari Books Controller
         * @param BookController
         */
        Route::apiResource('books',BookController::class)->names('books');

        Route::get("books/{book}/authors", function (){
            return true;
        })->name("book.authors");

        Route::get("books/{book}/relationships/authors", [BooksAuthorsRelationshipsController::class,"index"])
        ->name("books.relationships.authors");

        Route::match(["patch","put"],"books/{book}/relationships/authors",
            [BooksAuthorsRelationshipsController::class, "update"])
            ->name("books.relationships.authors");

        Route::get('books/{book}/authors',[BookAuthorsRelatedController::class, "index"])
            ->name("books.authors");
    });
