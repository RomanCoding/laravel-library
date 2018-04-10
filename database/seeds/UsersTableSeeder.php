<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'email' => '152roma@gmail.com',
            'first_name' => 'Roman',
            'last_name' => 'Hanoshenko',
            'access_level' => 3,
            'password' => bcrypt('password')
        ]);
        factory(App\User::class, 50)->create();
    }
}
