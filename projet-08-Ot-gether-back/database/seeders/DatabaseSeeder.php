<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(20)->create();

        User::create([
            'pseudo' => 'Aline',
            'lastname' => 'Massard',
            'firstname' => 'Aline',
            'city' => 'city',
            'email' => 'aline@gmail.com',
            'password' => bcrypt('password'),
            'picture' => 'myPix.png',
            'is_admin' => 1,
        ]);

        User::create([
            'pseudo' => 'David8301',
            'lastname' => 'Casanova',
            'firstname' => 'David',
            'city' => 'La Garde',
            'email' => 'david@gmail.com',
            'password' => bcrypt('password'),
            'picture' => 'myPix.png',
            'is_admin' => 1,
        ]);

        User::create([
            'pseudo' => 'Mathieu',
            'lastname' => 'Breteau',
            'firstname' => 'Mathieu',
            'city' => 'bdx',
            'email' => 'mathieu@gmail.com',
            'password' => bcrypt('password'),
            'picture' => 'myPix.png',
            'is_admin' => 1,
        ]);

        User::create([
            'pseudo' => 'Greg',
            'lastname' => 'Baptiste',
            'firstname' => 'Grégory',
            'city' => 'Toulom ❤️',
            'email' => 'gregory@gmail.com',
            'password' => bcrypt('password'),
            'picture' => 'myPix.png',
            'is_admin' => 1,
        ]);

        $this->call(
            [
                EventTagSeeder::class,
                EventCategorySeeder::class,
                EventSeeder::class,
                MessageSeeder::class,
            ]
        );
    }
}
