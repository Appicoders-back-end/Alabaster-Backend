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
        Category::create(['name' => 'Custodial Engineer', 'image' => 'Custodial.png']);
        Category::create(['name' => 'Flooring Technician', 'image' => 'FloorTechnician.png']);
        Category::create(['name' => 'Maintenance Technician', 'image' => 'MaintenanceTechnician.png']);
        Category::create(['name' => 'Self-Contractor Custodial Enginner', 'name' => 'SelfContractor.png']);
    }
}
