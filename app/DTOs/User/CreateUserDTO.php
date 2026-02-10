<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\Enums\Roles\UserRoles;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\Password;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class CreateUserDTO extends ValidatedDTO
{
    public string $name;

    public string $email;

    public UserRoles $role;

    public string $password;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users,email', 'email'],
            'role' => ['required', new EnumRule(UserRoles::class)],
            'password' => [
                'required',
                Password::min(8)->max(255),
                'confirmed',
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
            'name' => new StringCast(),
            'email' => new StringCast(),
            'role' => new EnumCast(UserRoles::class),
            'password' => new StringCast(),
        ];
    }
}
