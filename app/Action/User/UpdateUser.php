<?php

declare(strict_types=1);

namespace App\Action\User;

use App\DTOs\User\UpdateUserDTO;
use App\Models\User;

class UpdateUser
{
    public function __construct(
        private FetchUser $fetch_user
    ) {}

    public function handle(string $id, UpdateUserDTO $dto): User
    {
        $user = $this->fetch_user->handle($id);
        $user->fill($dto->toArray());
        $user->save();

        return $user;
    }
}
