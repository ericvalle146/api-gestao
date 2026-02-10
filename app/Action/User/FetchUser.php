<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Models\User;

class FetchUser
{
    public function handle(string $id): User
    {
        return User::findOrFail($id);
    }
}
