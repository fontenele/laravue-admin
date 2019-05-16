<?php

use Illuminate\Database\Seeder;

class LaravueMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            'id' => 1,
            'name' => 'main',
            'text' => 'Main',
            'enabled' => true,
            'left' => true
        ]);
        DB::table('menus')->insert([
            'id' => 2,
            'name' => 'admin',
            'text' => 'Admin',
            'enabled' => true,
            'left' => true
        ]);
        DB::table('menu_items')->insert([
            'id' => 1,
            'menu_id' => 1,
            'text' => 'Login',
            'route' => '/login',
            'icon' => 'fa fa-sign-in',
            'show_if_logged' => false
        ]);
        DB::table('menu_items')->insert([
            'id' => 2,
            'menu_id' => 1,
            'text' => 'Logout',
            'route' => '/logout',
            'icon' => 'fa fa-sign-out',
            'show_if_logged' => true,
            'order' => 2
        ]);
        DB::table('menu_items')->insert([
            'id' => 3,
            'menu_id' => 1,
            'text' => 'Home',
            'route' => '/home',
            'icon' => 'fa fa-home',
            'show_if_logged' => true,
            'permission_name' => 'DASHBOARD',
            'order' => 1
        ]);

        DB::table('menu_items')->insert([
            'id' => 4,
            'menu_id' => 2,
            'text' => 'Users',
            'route' => '/users',
            'icon' => 'fa fa-users',
            'show_if_logged' => true,
            'permission_name' => 'USERS',
            'order' => 1
        ]);
    }
}
