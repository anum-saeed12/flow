<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@tcl.com', 'password'=>'111111','user_role'=>'admin'],
            ['name' => 'Employee', 'email' => 'employe@tcl.com', 'password'=>'111111','user_role'=>'employee'],
        ];

        foreach($users as $user)
            DB::table('users')->insert([
                'username' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'user_role' => $user['user_role']
            ]);
    }
}
