<?php

namespace Database\Seeders;

use App\Models\Gaun;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        User::create([
            'name' => 'Darwin',
            'email' => 'Darwin@edelweiss.com',
            'password' => bcrypt('password'),
            'is_verified' => true
        ]);
        $this->call(GaunSeeder::class);
        $this->call(PemesananSeeder::class);
    }
}
