<?php

declare(strict_types=1);

namespace App\Action\User\Auth;

use Illuminate\Http\Request;

class LogoutUser
{
    public function handle(Request $request): void
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();
    }
}
