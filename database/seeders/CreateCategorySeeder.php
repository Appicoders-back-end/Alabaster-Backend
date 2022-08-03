<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Custodial Engineer']);
        Category::create(['name' => 'Flooring Technician']);
        Category::create(['name' => 'Maintenance Technician']);
        Category::create(['name' => 'Self-Contractor Custodial Enginner']);
    }
}
