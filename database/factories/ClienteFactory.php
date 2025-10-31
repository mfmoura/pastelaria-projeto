<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition()
    {
        return [
            'nome'            => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'telefone'        => $this->faker->numerify('119#######'),
            'data_nascimento' => $this->faker->date('Y-m-d', '-18 years'),
            'endereco'        => $this->faker->streetAddress(),
            'complemento'     => $this->faker->secondaryAddress(),
            'bairro'          => $this->faker->citySuffix(),
            'cep'             => $this->faker->numerify('0####-###'),
        ];
    }
}
