<?php

declare(strict_types=1);

namespace App\Action\User\Auth;

use App\DTOs\User\Auth\LoginUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginUser
{
    public function handle(LoginUserDTO $dto)
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new UnauthorizedHttpException('', 'Email ou senha inválidos');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'data' => $user,
            'token' => $token,
        ];
    }
}
