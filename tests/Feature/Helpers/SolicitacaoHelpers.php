<?php

declare(strict_types=1);

namespace Tests\Feature\Helpers;

use App\Enums\Roles\UserRoles;
use App\Models\Solicitacao;
use App\Models\User;
use Database\Seeders\Permissions\CreateSolicitacaoPermissions;
use Database\Seeders\Permissions\CreateUserPermissions;
use Database\Seeders\Roles\CreateUserRoles;

class SolicitacaoHelpers
{
    public static function seedRolesAndPermissions(): void
    {
        (new CreateUserPermissions())->run();
        (new CreateSolicitacaoPermissions())->run();
        (new CreateUserRoles())->run();
    }

    public static function createSolicitacao(array $attributes = []): Solicitacao
    {
        return Solicitacao::factory()->create($attributes);
    }

    public static function createSolicitacaoForUser(User $user, array $attributes = []): Solicitacao
    {
        return Solicitacao::factory()->for($user, 'solicitante')->create($attributes);
    }

    public static function createUserWithRole(UserRoles $role): User
    {
        return User::factory()->create()->assignRole($role->value);
    }

    public static function createUserToken(UserRoles $role): array
    {
        $user = self::createUserWithRole($role);

        return [$user, $user->createToken('teste_token')->plainTextToken];
    }
}
