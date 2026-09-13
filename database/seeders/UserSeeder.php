<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tax_objects')->insert([
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
