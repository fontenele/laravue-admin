<?php

use Illuminate\Database\Seeder;

class LaravueUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create();
        DB::table('users')->insert([
            'id' => 101,
            'name' => 'Guilherme Fontenele',
            'email' => 'guilherme@fontenele.net',
            'password' => bcrypt('123456'),
            'created_at' => \Carbon\Carbon::now()
        ]);
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 101,
        ]);
        DB::table('users')->insert([
            'id' => 102,
            'name' => 'Simone Teles',
            'email' => 'siteles2@gmail.com',
            'password' => bcrypt('123456'),
            'created_at' => \Carbon\Carbon::now()
        ]);
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 102,
        ]);

    }
}
