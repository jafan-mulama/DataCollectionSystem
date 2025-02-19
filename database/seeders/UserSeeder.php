<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create lecturer users
        User::create([
            'name' => 'Lecturer One',
            'email' => 'lecturer1@example.com',
            'password' => Hash::make('lecturer123'),
            'role' => 'lecturer',
        ]);

        User::create([
            'name' => 'Lecturer Two',
            'email' => 'lecturer2@example.com',
            'password' => Hash::make('lecturer123'),
            'role' => 'lecturer',
        ]);

        // Create student users
        User::create([
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Student Two',
            'email' => 'student2@example.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Student Three',
            'email' => 'student3@example.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);
    }
}
