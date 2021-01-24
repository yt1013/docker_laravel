<?php

use App\User;
use Carbon\Carbon;
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
        User::insert([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => '管理者',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        User::insert([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'ユーザー',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        User::insert([
            'name' => 'viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
            'role' => '閲覧者',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
