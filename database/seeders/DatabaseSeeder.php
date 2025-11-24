<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call(UserSeeder::class);
        $this->call(GradoSeeder::class);
        $this->call(SeccionSeeder::class);
        $this->call(TipoActividadSeeder::class);
        $this->call(TestDataSeeder::class);
        $this->call(PadreSeeder::class);
    }
}
