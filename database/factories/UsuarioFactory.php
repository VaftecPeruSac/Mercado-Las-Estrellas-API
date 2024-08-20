<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
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
            'id_persona'=>$this->faker->id_persona(),
            'nombre_usuario'=>$this->faker->nombre_usuario(),
            'contrasenia'=>$this->faker->contrasenia(),
            'rol'=>$this->faker->rol(),
            'estado'=>$this->faker->estado(),
            'fecha_registro'=>$this->faker->fecha_registro(),
        ];
    }
}
