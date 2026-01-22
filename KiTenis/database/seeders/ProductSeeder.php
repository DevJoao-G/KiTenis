<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Popula a tabela de produtos com dados de exemplo
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nike Air Max 90',
                'description' => 'Tênis clássico da Nike com amortecimento Air Max. Perfeito para o dia a dia com estilo e conforto.',
                'price' => 599.90,
                'stock' => 50,
                'category' => 'masculino',
                'active' => true,
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'description' => 'Tênis de corrida com tecnologia Boost para máximo retorno de energia. Ideal para treinos intensos.',
                'price' => 799.90,
                'stock' => 30,
                'category' => 'feminino',
                'active' => true,
            ],
            [
                'name' => 'Puma Suede Classic',
                'description' => 'Ícone do streetwear desde 1968. Estilo atemporal em camurça premium.',
                'price' => 399.90,
                'stock' => 25,
                'category' => 'masculino',
                'active' => true,
            ],
            [
                'name' => 'Vans Old Skool',
                'description' => 'O clássico skatista que nunca sai de moda. Durabilidade e estilo garantidos.',
                'price' => 349.90,
                'stock' => 40,
                'category' => 'feminino',
                'active' => true,
            ],
            [
                'name' => 'Converse All Star Kids',
                'description' => 'O icônico Chuck Taylor adaptado para os pequenos. Conforto e estilo desde cedo.',
                'price' => 199.90,
                'stock' => 60,
                'category' => 'infantil',
                'active' => true,
            ],
            [
                'name' => 'New Balance 574',
                'description' => 'Combinação perfeita de herança e inovação. Conforto premium para uso diário.',
                'price' => 499.90,
                'stock' => 35,
                'category' => 'masculino',
                'active' => true,
            ],
            [
                'name' => 'Asics Gel-Kayano 29',
                'description' => 'Tênis de corrida com máxima estabilidade e amortecimento Gel. Para longas distâncias.',
                'price' => 899.90,
                'stock' => 20,
                'category' => 'feminino',
                'active' => true,
            ],
            [
                'name' => 'Reebok Classic Leather',
                'description' => 'Estilo retrô que atravessa gerações. Couro macio e design limpo.',
                'price' => 379.90,
                'stock' => 45,
                'category' => 'masculino',
                'active' => true,
            ],
            [
                'name' => 'Olympikus Kids Jump',
                'description' => 'Desenvolvido especialmente para crianças ativas. Leveza e proteção.',
                'price' => 149.90,
                'stock' => 70,
                'category' => 'infantil',
                'active' => true,
            ],
            [
                'name' => 'Mizuno Wave Prophecy',
                'description' => 'Tecnologia Wave exclusiva para amortecimento e estabilidade superiores.',
                'price' => 1199.90,
                'stock' => 15,
                'category' => 'masculino',
                'active' => true,
            ],
            [
                'name' => 'Fila Disruptor II',
                'description' => 'O chunky sneaker que dominou a moda. Atitude e conforto em cada passo.',
                'price' => 449.90,
                'stock' => 28,
                'category' => 'feminino',
                'active' => true,
            ],
            [
                'name' => 'Skechers D\'Lites Kids',
                'description' => 'Estilo chunky para os pequenos. Confortável e cheio de personalidade.',
                'price' => 229.90,
                'stock' => 50,
                'category' => 'infantil',
                'active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}