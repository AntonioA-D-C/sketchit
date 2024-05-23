<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $categories = [
        ["name"=>"Landmarks"],
        ["name"=>"Architecture"],
        ["name"=>"Cityscape"],
        ["name"=>"Neighborhood"],
        ["name"=>"Transportation"],
        ["name"=>"Industrial"],
        ["name"=>"Historical Sites"],
        ["name"=>"Skylines"],
        ["name"=>" People"],
        
      ];

      foreach($categories as $category){
        category::create($category);
      }

    }
}
