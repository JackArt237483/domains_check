<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Domain;
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
      // Очищаем таблицу перед вставкой, чтобы избежать дубликатов
        DB::table('domains')->truncate();

        $domains = [
            [
                'name' => 'example.com',
                'is_registered' => true,
                'expires_at' => Carbon::now()->addYear(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'test.org',
                'is_registered' => true,
                'expires_at' => Carbon::now()->addMonths(6),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'free.net',
                'is_registered' => false, // Свободный домен
                'expires_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'mydomain.ru',
                'is_registered' => true,
                'expires_at' => Carbon::now()->addDays(30),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Domain::insert($domains);
    }
}
