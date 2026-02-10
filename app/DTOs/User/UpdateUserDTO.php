<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\Enums\Roles\UserRoles;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\Password;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class UpdateUserDTO extends ValidatedDTO
{
    protected function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . request()->route('user')],
            'role' => ['sometimes', 'string', new EnumRule(UserRoles::class)],
            'password' => [
                'sometimes',
                'string',
                Password::min(8)
                    ->max(255), 'confirmed'],
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
