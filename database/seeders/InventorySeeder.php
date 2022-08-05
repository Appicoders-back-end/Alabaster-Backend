<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventory::create(['name' => 'Paper Towel']);
        Inventory::create(['name' => 'Mop']);
        Inventory::create(['name' => 'Wiper']);
        Inventory::create(['name' => 'Bucket']);
        Inventory::create(['name' => 'Brush']);
        Inventory::create(['name' => 'Plunger']);
        Inventory::create(['name' => 'Soap']);
    }
}
