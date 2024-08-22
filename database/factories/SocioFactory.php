<?php

namespace Database\Factories;
use App\Models\Usuario;
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
            'id_usuario'=> Usuario::factory(),
            'tipo_persona'=>$this->faker->tipo_persona(),
            'estado'=>$this->faker->estado(),
            'fecha_registro'=>$this->faker->fecha_registro(),
        ];
    }
}
