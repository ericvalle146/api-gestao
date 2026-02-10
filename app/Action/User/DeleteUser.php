<?php

declare(strict_types=1);

namespace App\Action\User;

class DeleteUser
{
    public function __construct(
        private FetchUser $fetch_user
    ) {}

    public function handle(string $id)
    {
        $this->fetch_user->handle($id)->delete();
    }
}
