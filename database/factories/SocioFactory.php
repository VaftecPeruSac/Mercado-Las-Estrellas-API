<?php

namespace Database\Factories;

use App\Models\GiroNegocio;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Socio>
 */
class SocioFactory extends Factory
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
            'id_gironegocio'=>GiroNegocio::factory(),
            'correo'=>$this->faker->correo(),
            'telefono'=>$this->faker->telefono(),
            'estado'=>$this->faker->estado(),
            'id_persona'=> Persona::factory(),
        ];
    }
}
