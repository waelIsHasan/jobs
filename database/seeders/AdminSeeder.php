<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
        
            'first_name' => 'empco',
            'last_name' => 'admin',
            'email' => 'empco@gmail.come',
            'password' => Hash::make('empco8'),
           ]);
    }
}
