<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRole;
use App\Models\Statement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => UserRole::USER->value]);
        $role1->givePermissionTo('edit articles');
        $role1->givePermissionTo('delete articles');

        $role2 = Role::create(['name' => UserRole::ADMIN->value]);
        $role2->givePermissionTo('publish articles');
        $role2->givePermissionTo('unpublish articles');

        $admin = User::factory()->create([
            'full_name' => 'John Doe',
            'birthday'  => Carbon::createFromDate('1980', '01', '01'),
            'email'     => 'test@example.com',
        ]);
        $admin->assignRole($role1);

        $user = User::factory()->create([
            'full_name' => 'Mike Johnson',
            'birthday'  => Carbon::createFromDate('1990', '01', '01'),
            'email'     => 'user@example.com',
        ]);
        $user->assignRole($role2);

        Statement::factory()->create([
            'user_id' => $user->id,
            'title'   => "Test Statement",
        ]);
    }
}
