<?php

declare(strict_types=1);

namespace App\Action\User;

use App\DTOs\User\CreateUserDTO;
use App\Models\User;

class CreateUser
{
    public function handle(CreateUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'role' => $dto->role->value,
            'password' => $dto->password,
        ]);
        $user->assignRole($dto->role);

        return $user;
    }
}
