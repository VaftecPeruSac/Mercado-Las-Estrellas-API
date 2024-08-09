<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Persona::factory()
        ->count(25)
        ->hasPuestos(10)
        ->create();
        Persona::factory()
        ->count(100)
        ->hasPuestos(3)
        ->create();
        Persona::factory()
        ->count(100)
        ->hasPuestos(1)
        ->create();
        Persona::factory()
        ->count(5)
        ->create();

    }
}
