<?php

use App\Http\Controllers\Authors\AuthorController;
use App\Http\Controllers\Authors\AuthorsBooksController;
use App\Http\Controllers\Books\BookAuthorsRelatedController;
use App\Http\Controllers\Books\BookController;
use App\Http\Controllers\Books\BooksAuthorsRelationshipsController;
use App\Http\Controllers\Comments\CommentsController;
use App\Http\Controllers\Comments\CurrentAuthenticatedUserController;
use App\Http\Controllers\Users\UsersController;
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

        /*
         * -------------------
         * AUTHORS API ROUTING
         * -------------------
         */
        Route::apiResource('authors', AuthorController::class)->names('authors');
        Route::get("authors/{id}/relationships/books", [AuthorsBooksController::class, "index"])
            ->name("authors.relationships.books");
        Route::match(["PUT", "PATCH"], "authors/{id}/relationships/books", [AuthorsBooksController::class, "index"])
            ->name("authors.relationships.books");
        Route::get("authors/{id}/books",function($request){
            return $request;
        })->name("authors.books");


        /*
         * ------------------
         * BOOKS API ROUTING
         * ------------------
         */
        Route::apiResource('books', BookController::class)->names('books');
        Route::get("books/{book}/relationships/authors", [BooksAuthorsRelationshipsController::class, "index"])
            ->name("books.relationships.authors");
        Route::match(["patch", "put"], "books/{book}/relationships/authors",
            [BooksAuthorsRelationshipsController::class, "update"])
            ->name("books.relationships.authors");
        Route::get('books/{book}/authors', [BookAuthorsRelatedController::class, "index"])
            ->name("books.authors");

        /*
         * -----------------
         * USERS API ROUTING
         * -----------------
         */
        Route::apiResource('users',UsersController::class)->names('users');
        Route::get("/users/current", [CurrentAuthenticatedUserController::class, "show"]);
        Route::get("users/{user}/relationships/comments", [BooksAuthorsRelationshipsController::class, "index"])
            ->name("users.relationships.comments");
        Route::match(["patch", "put"], "users/{user}/relationships/comments",
            [BooksAuthorsRelationshipsController::class, "update"])
            ->name("users.relationships.comments");
        Route::get('users/{user}/comments', [BookAuthorsRelatedController::class, "index"])
            ->name("users.comments");

        /*
         * --------------------
         * COMMENTS API ROUTING
         * --------------------
         */
        Route::apiResource("comments",CommentsController::class)
            ->names("comments");
        Route::get("comments/{comment}/relationships/users", [CommentsController::class, "index"])
            ->name("comments.relationships.users");
        Route::match(["PATCH","PUT"],"comments/{comment}/relationships/users", [CommentsController::class, "update"])
            ->name("comments.relationships.users");
        Route::get("comments/{comment}/users", [CommentsController::class, "show"])
            ->name("comments.users");

        Route::get("comments/{comment}/relationships/books", [CommentsController::class, "index"])
            ->name("comments.relationships.books");
        Route::get("comments/{comment}/relationships/books", [CommentsController::class, "update"])
            ->name("comments.relationships.books");
        Route::get("comments/{comment}/books", [CommentsController::class, "show"])
            ->name("comments.books");
    });
