<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('domains')->insert([
        //     [
        //         'name' => 'example.com', 
        //         'is_registered' => true,
        //         'expires_at' => Carbon::now()->addYear(),
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'name' => 'test.com',
        //         'is_registered' => true,
        //         'expires_at' => Carbon::now()->addMonths(6),
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        // ]);
    }
}
