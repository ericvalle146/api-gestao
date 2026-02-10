<?php

declare(strict_types=1);

namespace Tests\Feature\Helpers;

use App\Models\User;
use Database\Seeders\Permissions\CreateSolicitacaoPermissions;
use Database\Seeders\Permissions\CreateUserPermissions;
use Database\Seeders\Roles\CreateUserRoles;

class UserHelpers
{
    public static function createUser()
    {
        return User::factory()->create();
    }

    public static function createAdmin()
    {
        (new CreateUserPermissions())->run();
        (new CreateSolicitacaoPermissions())->run();
        (new CreateUserRoles())->run();

        return User::factory()->create()->assignRole('admin');
    }

    public static function createAdminToken()
    {
        $admin = self::createAdmin();

        return [$admin, $admin->createToken('teste_token')->plainTextToken];
    }

    public static function createUserDataFaker()
    {
        return User::factory()->make();
    }
}
