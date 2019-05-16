<?php

use Illuminate\Database\Seeder;

class LaravueRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'ADMIN',
            'label' => 'Administrador'
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'CAIXA',
            'label' => 'Caixa'
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'CLIENTE',
            'label' => 'Cliente'
        ]);

        DB::table('permissions')->insert([
            'id' => 1,
            'name' => 'DASHBOARD',
            'label' => 'Dashboard'
        ]);
        DB::table('permissions')->insert([
            'id' => 2,
            'name' => 'USERS',
            'label' => 'Users'
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 1,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => 2,
            'permission_id' => 1,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 1,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 2,
        ]);
    }
}
