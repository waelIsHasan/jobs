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
        
            'categoryJob' => 'Transportation',
            
           ]);
           
           Category::create([
        
            'categoryJob' => 'It',
            
           ]);
           Category::create([
        
            'categoryJob' => 'Ai',
            
           ]);
           Category::create([
        
            'categoryJob' => 'Marketing',
            
           ]);
           Category::create([
        
            'categoryJob' => 'Education',
            
           ]);
           Category::create([
        
            'categoryJob' => 'Healthcare',
            
           ]);
    
    }
}
