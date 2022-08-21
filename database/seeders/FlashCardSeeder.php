<?php

namespace Database\Seeders;

use App\Models\FlashCard;
use Illuminate\Database\Seeder;

class FlashCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        FlashCard::factory()
            ->count(10)
            ->create()
        ;
    }
}
