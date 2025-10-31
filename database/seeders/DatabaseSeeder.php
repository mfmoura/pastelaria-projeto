<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Produto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedProdutos();
    }

    /**
     * Cria um usuário admin padrão (admin:admin)
     */
    private function seedAdminUser(): void
    {
        User::updateOrCreate(
            [ 'email' => 'admin@admin.com' ],
            [
                'name'     => 'Admin',
                'password' => bcrypt('admin'),
            ]
        );
    }

    /**
     * Popula a tabela de produtos com fotos e valores
     */
    private function seedProdutos(): void
    {
        $produtos = [
            [
                'nome'   => 'Pastel de Carne',
                'preco'  => 8.50,
                'imagem' => 'produtos/carne.jpg',
            ],
            [
                'nome'   => 'Pastel de Queijo',
                'preco'  => 7.50,
                'imagem' => 'produtos/queijo.jpg',
            ],
            [
                'nome'   => 'Pastel Napolitano',
                'preco'  => 9.00,
                'imagem' => 'produtos/napolitano.jpg',
            ],
            [
                'nome'   => 'Pastel de Frango com Catupiry',
                'preco'  => 10.00,
                'imagem' => 'produtos/frango.jpg',
            ],
            [
                'nome'   => 'Pastel de Calabresa',
                'preco'  => 9.50,
                'imagem' => 'produtos/calabresa.jpg',
            ],
            [
                'nome'   => 'Coca-Cola Lata',
                'preco'  => 6.00,
                'imagem' => 'produtos/cocacola.jpg',
            ],
            [
                'nome'   => 'Guaraná Antártica Lata',
                'preco'  => 5.50,
                'imagem' => 'produtos/guarana.jpg',
            ],
            [
                'nome'   => 'Fanta Laranja Lata',
                'preco'  => 5.50,
                'imagem' => 'produtos/fanta.jpg',
            ],
            [
                'nome'   => 'Sprite Lata',
                'preco'  => 5.50,
                'imagem' => 'produtos/sprite.jpg',
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::firstOrCreate(
                [ 'nome' => $produto['nome'] ],
                [
                    'preco'  => $produto['preco'],
                    'foto' => $produto['imagem'],
                ]
            );
        }
    }
}
