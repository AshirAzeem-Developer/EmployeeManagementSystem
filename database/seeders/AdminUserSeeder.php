<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com', // Yeh email use karein
            'password' => Hash::make('admin@123'), // Apni pasand ka password rakhein
            'role' => 'admin', // Yahan role 'admin' set kiya
            'department_id' => 1,
            'shift_id' => 1,
        ]);
    }
}
