<?php

declare(strict_types=1);

namespace App\DTOs\User\Auth;

use Illuminate\Validation\Rules\Password;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class LoginUserDTO extends ValidatedDTO
{
    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->max(255),
            ],
        ];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'email' => new StringCast(),
            'password' => new StringCast(),
        ];
    }
}
