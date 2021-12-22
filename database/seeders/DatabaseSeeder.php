<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $app_mode = env('APP_MODE', 'production');

        Type::create([
            'name' => 'PG'
        ]);
        Type::create([
            'name' => 'Essay'
        ]);

        if ($app_mode == 'development') {
            User::factory(10)->create();
            Test::factory(5)->create();
            Quiz::factory(100)->create();
            Choice::factory(2000)->create();
        } else if ($app_mode == 'production') {
            User::create([
                'username' => 'athatsaqif',
                'name' => 'Muhammad Atha Tsaqif',
                'is_admin' => true,
                'super_admin' => true,
                'class' => 'Admin',
                'password' => Hash::make('password')
            ]);
        }
    }
}
