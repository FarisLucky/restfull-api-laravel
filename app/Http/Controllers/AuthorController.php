<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsCollection;
use App\Http\Resources\AuthorsResource;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display Listing of Authors
     * @return AuthorsCollection
     */
    public function index()
    {
        $authors = Author::limit(3)->orderBy('name')->get();
//        $authors = DB::table('authors')->limit(500)->get();
        return new AuthorsCollection($authors);
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
     * @param CreateAuthorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAuthorRequest $request)
    {
        $author = Author::create($request->input("data.attributes"));
        return (new AuthorsResource($author))
            ->response()
            ->setStatusCode(201)
            ->header('Location',route('authors.show',[
                'author' => $author
            ]));
    }

    /**
     * @param Author $author
     * @return AuthorsResource
     */
    public function show(Author $author)
    {
        return new AuthorsResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * @param UpdateAuthorRequest $request
     * @param Author $author
     * @return AuthorsResource
     */
    public function update(UpdateAuthorRequest $request, Author $author): AuthorsResource
    {
        $author->update($request->input('data.attributes'));
        return new AuthorsResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return response()
            ->json(null, 200);
    }
}
