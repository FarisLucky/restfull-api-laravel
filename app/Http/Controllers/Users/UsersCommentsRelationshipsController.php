<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\User;
use App\Services\JSONAPIService;

class UsersCommentsRelationshipsController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    /**
     * UsersCommentsRelationshipsController constructor.
     * @param JSONAPIService $service
     */
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function index(User $user)
    {
        return $this->service->fetchRelationship($user, 'comments');
    }

    public function update(JSONAPIRelationshipRequest $request, User $user)
    {
        return $this->service->updateToManyRelationships($user, 'comments', $request->input('data.*.id'));
    }
}
