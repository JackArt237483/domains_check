<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Заполняет таблицу users тестовым пользователем.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Artem User',
            'email' => 'Artem23@mail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
