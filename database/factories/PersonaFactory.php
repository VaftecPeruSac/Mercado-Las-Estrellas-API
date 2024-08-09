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
        $nombre_completo = $this->faker->nombre()+' '+$this->faker->apellidoP()+' '+ $this->faker->apellidoM();
        // $type = $this->faker->ramdonElement(['I','B']);
        // $name  = $type == 'I'  ?   $this->faker->name():$this->faker->company();
        return [
            //
            'nombre'=>$this->faker->nombre(),
            'nombre_completo'=>$nombre_completo,
            'apellidoP'=>$this->faker->apellidoP(),
            'apellidoM'=>$this->faker->apellidoM(),
            'dni'=>$this->faker->dni(),
            'estado'=>$this->faker->estado(),
        ];
    }
}
