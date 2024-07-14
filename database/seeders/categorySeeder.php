<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
        
            'categoryJob' => 'transportation',
            
           ]);
           
           Category::create([
        
            'categoryJob' => 'it',
            
           ]);
           Category::create([
        
            'categoryJob' => 'ai',
            
           ]);
           Category::create([
        
            'categoryJob' => 'marketing',
            
           ]);
           Category::create([
        
            'categoryJob' => 'education',
            
           ]);
           Category::create([
        
            'categoryJob' => 'healthcare',
            
           ]);
    
    }
}
