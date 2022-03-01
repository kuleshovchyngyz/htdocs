<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
        	[
        		'name' => 'admin chyngyz', 
        		'email' => 'kuleshov.chyngyz@gmail.com',
        		'password' => Hash::make('pass'),
        	],
            [
                'name' => 'admin pavel', 
                'email' => 'paulic@list.ru',
                'password' => Hash::make('fkbyf90'),
            ],
            [
                'name' => 'manager',
                'email' => 'manager@local.loc',
                'password' => Hash::make('pass'),
            ],
        	[
        		'name' => 'client', 
        		'email' => 'client@local.loc',
        		'password' => Hash::make('pass'),
        	],
        ]);
    }
}
