<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        $roles = [
            'super-admin',
            'agency-admin',
            'agency-manager',
            'agency-staff',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'backoffice',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | (Optional) Permissions
        |--------------------------------------------------------------------------
        | Add later if needed – structure already ready
        */
        $permissions = [
            'view dashboard',
            'manage agencies',
            'manage users',
            'manage vehicles',
            'manage bookings',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'backoffice',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Assign permissions
        |--------------------------------------------------------------------------
        */
        Role::findByName('super-admin', 'backoffice')
            ->syncPermissions(Permission::all());

        Role::findByName('agency-admin', 'backoffice')
            ->syncPermissions([
                'view dashboard',
                'manage users',
                'manage vehicles',
                'manage bookings',
                'view reports',
            ]);

        Role::findByName('agency-manager', 'backoffice')
            ->syncPermissions([
                'view dashboard',
                'manage vehicles',
                'manage bookings',
                'view reports',
            ]);

        Role::findByName('agency-staff', 'backoffice')
            ->syncPermissions([
                'view dashboard',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Assign roles to users
        |--------------------------------------------------------------------------
        */
        $superAdmin = User::where('email', 'admin@agency1.com')->first();
        if ($superAdmin) {
            $superAdmin->syncRoles(['super-admin']);
        }

        $this->command?->info('Roles & permissions seeded ✅');
        $this->command?->info('Super Admin assigned to admin@agency1.com');
    }
}