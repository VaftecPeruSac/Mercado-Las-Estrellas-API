<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre_completo = $this->faker->nombre()+' '+$this->faker->apellido_paterno()+' '+ $this->faker->apellido_materno();
        return [
            //
            'nombre'=>$this->faker->nombre(),
            'nombre_completo'=>$nombre_completo,
            'apellido_paterno'=>$this->faker->apellido_paterno(),
            'apellido_materno'=>$this->faker->apellido_materno(),
            'dni'=>$this->faker->dni(),
            'correo'=>$this->faker->correo(),
            'telefono'=>$this->faker->telefono(),
            'estado'=>$this->faker->estado(),
            'fecha_registro'=>$this->faker->fecha_registro(),
        ];
    }
}
