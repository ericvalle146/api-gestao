<?php

declare(strict_types=1);

namespace Database\Seeders\Roles;

use App\Enums\Roles\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateUserRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRoles::all() as $role) {
            $createdRole = Role::firstOrCreate([
                'name' => $role->value,
                'guard_name' => 'sanctum',
            ]);

            $permissions = array_map(fn ($p) => $p->value, $role->permissions());
            $createdRole->syncPermissions($permissions);
        }
    }
}
