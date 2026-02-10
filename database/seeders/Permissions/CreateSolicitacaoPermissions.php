<?php

declare(strict_types=1);

namespace Database\Seeders\Permissions;

use App\Enums\Permissions\SolicitacaoPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreateSolicitacaoPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SolicitacaoPermissions::all() as $permissions) {
            Permission::create([
                'name' => $permissions->value,
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
