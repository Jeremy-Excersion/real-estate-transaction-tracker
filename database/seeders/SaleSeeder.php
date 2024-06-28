<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\User;
use League\Csv\Reader;
use App\Models\SaleFee;
use App\Models\SaleSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ignore constraints then truncate sale and sale fee tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Sale::truncate();
        SaleFee::truncate();
        SaleSource::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Generate 100 sales with fees and sources using SaleFactory
        Sale::factory()
            ->count(100)
            ->hasFees(3)
            ->hasSources(1)
            ->create();
    }
}