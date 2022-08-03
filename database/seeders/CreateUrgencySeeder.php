<?php

namespace Database\Seeders;

use App\Models\Urgency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUrgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Urgency::create(['name' => 'Emergency']);
        Urgency::create(['name' => 'One Day']);
        Urgency::create(['name' => 'This Week']);
        Urgency::create(['name' => 'When Possible']);
    }
}
