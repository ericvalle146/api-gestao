<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Action\User\Auth\FetchAuthenticatedUser;
use App\Action\User\Auth\LoginUser;
use App\Action\User\Auth\LogoutUser;
use App\DTOs\User\Auth\LoginUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\LoginResource;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function login(LoginUserDTO $dto, LoginUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new LoginResource($action->handle($dto)));
    }

    public function logout(Request $request, LogoutUser $action): NoContentResponse
    {
        $action->handle($request);

        return new NoContentResponse();
    }

    public function me(Request $request, FetchAuthenticatedUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($request)));
    }
}
