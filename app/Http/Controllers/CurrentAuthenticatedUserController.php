<?php

namespace App\Http\Controllers;

use App\Http\Resources\JSONAPIResource;
use Illuminate\Http\Request;

class CurrentAuthenticatedUserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JSONAPIResource
     */
    public function show(Request $request)
    {
        return new JSONAPIResource($request->user());
    }

}
