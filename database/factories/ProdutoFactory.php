<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Produto;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        Storage::fake('public');

        $imagem = UploadedFile::fake()->image(
            'foto.jpg',
            800,
            600
        );

        $path = $imagem->store('produtos', 'public');

        return [
            'nome'  => $this->faker->words(3, true),
            'preco' => $this->faker->randomFloat(2, 1, 200),
            'foto'  => $path,
        ];
    }
}
