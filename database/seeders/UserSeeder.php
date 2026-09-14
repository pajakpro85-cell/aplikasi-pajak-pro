<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_user' => 1,
                'nama_user' => 'Admin',
                'email' => 'pajakpro85@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ]
        ]);
    }
}
