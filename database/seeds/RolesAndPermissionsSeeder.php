<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //create roles
        $clientRole = Role::create(['name' => 'client']);
        $managerRole = Role::create(['name' => 'manager']);
        $superAdminRole = Role::create(['name' => 'super-admin']);


        // create permissions
        Permission::create(['name' => 'view project']);
        Permission::create(['name' => 'add project']);
        Permission::create(['name' => 'edit project']);
        Permission::create(['name' => 'destroy project']);

        Permission::create(['name' => 'view group']);
        Permission::create(['name' => 'add group']);
        Permission::create(['name' => 'edit group']);
        Permission::create(['name' => 'destroy group']);

        Permission::create(['name' => 'view query']);
        Permission::create(['name' => 'add query']);
        Permission::create(['name' => 'edit query']);
        Permission::create(['name' => 'destroy query']);

        Permission::create(['name' => 'view region']);
        Permission::create(['name' => 'add region']);
        Permission::create(['name' => 'edit region']);
        Permission::create(['name' => 'destroy region']);

        
        // this can be done as separate statements
        $clientRole->givePermissionTo([
        	'view project', 'view group', 'view query', 'view region'
        ]);
        $managerRole->givePermissionTo(Permission::all());
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
