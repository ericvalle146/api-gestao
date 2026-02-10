<?php

declare(strict_types=1);

namespace App\Action\User\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class FetchAuthenticatedUser
{
    public function handle(Request $request): User
    {
        return $request->user();
    }
}
