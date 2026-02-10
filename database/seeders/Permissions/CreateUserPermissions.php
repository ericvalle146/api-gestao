<?php

declare(strict_types=1);

namespace Database\Seeders\Permissions;

use App\Enums\Permissions\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreateUserPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserPermissions::all() as $number) {
            Permission::create([
                'name' => $number->value,
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
