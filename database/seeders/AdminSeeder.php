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
            'fcm_token' =>'d5WoktU9Sn6ZVuYtCwBH3K:APA91bHerZbtVH67vD1yT3VMEc04fOGAvUYMhBAsfQXV2n97ltM_JTvssu9qF4FYIuiOQvDmAxNC4PNetgJAH4xs5wmbZqVJu8uHCz71lRq87va8fDFFS2_XRWy3NjURWXaDQg1Tnvy9', 
            'password' => Hash::make('empco8'),
           ]);
    }
}
