<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        DB::table('users')->insert([
            'name' => 'System Administrator',
            'email' => 'system.admin@acrnhealth.com',
            'password' => Hash::make('password'), 
            'user_type' => 1, 
            'department' => 'Information Technology',
            'job_title' => 'System Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Regular users
        DB::table('users')->insert([
            [
                'name' => 'ROPA Admin',
                'email' => 'systemadmin@gmail.com',
                'password' => Hash::make('password'),
                'user_type' => 0,
                'department' => 'Research & Development',
                'job_title' => 'Data Analyst',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blessward Mutsotso',
                'email' => 'acrndev@gmail.com',
                'password' => Hash::make('password'),
                'user_type' => 0,
                'department' => 'Software Developer',
                'job_title' => 'Software Developer ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
