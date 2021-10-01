<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=10; $i++){
            Category::create(['name'=>"category $i",'image'=>"assets/img/vegi.png"]);
        }
    }
}
