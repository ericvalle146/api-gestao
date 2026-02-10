<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permissions\SolicitacaoPermissions;
use App\Enums\Permissions\UserPermissions;
use App\Models\User;
use Database\Seeders\Permissions\CreateSolicitacaoPermissions;
use Database\Seeders\Permissions\CreateUserPermissions;
use Database\Seeders\Roles\CreateUserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call(CreateUserRoles::class);
        $this->call(CreateUserPermissions::class);
        $this->call(CreateSolicitacaoPermissions::class);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $admin->assignRole('admin');

        foreach (array_merge(UserPermissions::all(), SolicitacaoPermissions::all()) as $permissions) {
            $admin->givePermissionTo($permissions->value);
        }
    }
}
