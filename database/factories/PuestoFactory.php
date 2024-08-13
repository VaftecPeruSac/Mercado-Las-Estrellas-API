<?php

namespace Database\Factories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Persona;
use App\Models\Socio;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Puesto>
 */
class PuestoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'nombre'=>$this->faker->nombre(),
            'id_socio'=> Socio::factory(),
            'id_block'=> Block::factory(),
            'area'=>$this->faker->area(),
            'estado'=>$this->faker->estado(),
            // 'fecha_registro'=>$this->faker->dateTimeThisDecade()
        ];
    }
}
