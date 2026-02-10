<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Action\User\CreateUser;
use App\Action\User\DeleteUser;
use App\Action\User\FetchListUser;
use App\Action\User\FetchUser;
use App\Action\User\UpdateUser;
use App\DTOs\Common\PaginationDTO;
use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginationDTO $dto, FetchListUser $action): JsonResponse
    {
        return UserResource::collection($action->handle($dto))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserDTO $dto, CreateUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($dto)), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, FetchUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($id)));
    }

    // ApiSuccessResponse
    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, UpdateUserDTO $dto, UpdateUser $action)
    {
        return new ApiSuccessResponse(new UserResource($action->handle($id, $dto)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DeleteUser $action): NoContentResponse
    {
        $action->handle($id);

        return new NoContentResponse();
    }
}
