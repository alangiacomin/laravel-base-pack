<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser == null)
        {
            $adminUser = User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ]);
        }

        $adminRole = Role::findOrCreate('admin');

        $userPermissions = [
            'viewAny',
            'create',
            'view',
            'update',
            'delete',
            'restore',
            'forceDelete',
        ];

        foreach ($userPermissions as $up)
        {
            Permission::findOrCreate("user-{$up}");
        }

        $adminUser->assignRole($adminRole);

        $testUser = User::where('email', 'user@user.com')->first();
        if ($testUser == null)
        {
            $testUser = User::create([
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
