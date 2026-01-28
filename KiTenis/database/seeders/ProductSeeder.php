<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        $now = now();

        // Se as colunas de promoção existirem (depois da migration), vamos seedar promo também
        $hasPromoColumns =
            Schema::hasColumn('products', 'discount_percentage')
            && Schema::hasColumn('products', 'promotion_start')
            && Schema::hasColumn('products', 'promotion_end')
            && Schema::hasColumn('products', 'featured_in_carousel');

        $products = [
            // -------------------------
            // MASCULINO / UNISSEX
            // -------------------------
            [
                'name' => 'Nike Air Max 90',
                'description' => 'Clássico da Nike com amortecimento Air. Conforto e estilo pro dia a dia.',
                'price' => 599.90, 'stock' => 50, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Nike Air Force 1 \'07',
                'description' => 'O ícone do streetwear com couro e sola robusta. Combina com tudo.',
                'price' => 649.90, 'stock' => 40, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Nike Air Max 270',
                'description' => 'Air unit grande no calcanhar para amortecimento macio e visual moderno.',
                'price' => 799.90, 'stock' => 28, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Nike Dunk Low',
                'description' => 'Clássico do basquete que virou lifestyle. Visual retrô e versátil.',
                'price' => 749.90, 'stock' => 20, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Jordan 1 Mid',
                'description' => 'Silhueta icônica da linha Jordan. Estilo casual com pegada premium.',
                'price' => 999.90, 'stock' => 18, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Adidas Superstar',
                'description' => 'O clássico com biqueira shell toe. Street e atemporal.',
                'price' => 499.90, 'stock' => 35, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Adidas Samba OG',
                'description' => 'Silhueta retrô com muita presença. Um dos modelos mais famosos da Adidas.',
                'price' => 699.90, 'stock' => 22, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Adidas Gazelle',
                'description' => 'Modelo clássico em camurça, super versátil para looks casuais.',
                'price' => 649.90, 'stock' => 26, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'New Balance 574',
                'description' => 'Herança e conforto. Um dos modelos mais tradicionais da New Balance.',
                'price' => 499.90, 'stock' => 30, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'New Balance 550',
                'description' => 'Visual retrô de basquete com construção robusta e confortável.',
                'price' => 899.90, 'stock' => 16, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Asics GEL-Lyte III',
                'description' => 'Clássico lifestyle com conforto e design marcante (língua dividida).',
                'price' => 699.90, 'stock' => 14, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Reebok Club C 85',
                'description' => 'Minimalista e clássico. Couro e visual clean.',
                'price' => 449.90, 'stock' => 25, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Puma Suede Classic',
                'description' => 'Ícone da Puma em camurça com pegada retrô.',
                'price' => 399.90, 'stock' => 22, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Vans Old Skool',
                'description' => 'O clássico do skate com a sidestripe. Muito resistente.',
                'price' => 349.90, 'stock' => 40, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Converse Chuck 70',
                'description' => 'Versão premium do clássico. Mais confortável e com acabamento superior.',
                'price' => 429.90, 'stock' => 19, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Mizuno Wave Rider',
                'description' => 'Corrida com estabilidade e amortecimento Wave, ótimo para rodagem.',
                'price' => 899.90, 'stock' => 12, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Olympikus Corre',
                'description' => 'Corrida nacional com ótimo custo-benefício e conforto no dia a dia.',
                'price' => 299.90, 'stock' => 45, 'category' => 'masculino', 'active' => true,
            ],
            [
                'name' => 'Under Armour HOVR Sonic',
                'description' => 'Tênis de treino/corrida com boa resposta e conforto.',
                'price' => 549.90, 'stock' => 18, 'category' => 'masculino', 'active' => true,
            ],

            // -------------------------
            // FEMININO / UNISSEX
            // -------------------------
            [
                'name' => 'Adidas Ultraboost',
                'description' => 'Tecnologia Boost para retorno de energia e conforto máximo.',
                'price' => 899.90, 'stock' => 20, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Nike Air Max 97',
                'description' => 'Linhas inspiradas em velocidade e amortecimento Air por toda a sola.',
                'price' => 999.90, 'stock' => 12, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Nike Air Max 1',
                'description' => 'O primeiro Air Max. Clássico e elegante.',
                'price' => 899.90, 'stock' => 14, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Adidas Stan Smith',
                'description' => 'Tênis clean e icônico, combina com qualquer look.',
                'price' => 499.90, 'stock' => 28, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Fila Disruptor II',
                'description' => 'Chunky sneaker marcante, tendência e presença.',
                'price' => 449.90, 'stock' => 18, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'New Balance 327',
                'description' => 'Modelo lifestyle com pegada retrô e muito confortável.',
                'price' => 699.90, 'stock' => 15, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Asics GEL-Kayano',
                'description' => 'Estabilidade e amortecimento para corrida. Excelente para longas distâncias.',
                'price' => 999.90, 'stock' => 10, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Puma RS-X',
                'description' => 'Visual robusto com conforto e vibe street.',
                'price' => 599.90, 'stock' => 16, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Reebok Classic Leather',
                'description' => 'Couro macio e visual retrô. Um clássico.',
                'price' => 399.90, 'stock' => 22, 'category' => 'feminino', 'active' => true,
            ],
            [
                'name' => 'Vans Sk8-Hi',
                'description' => 'Cano alto clássico. Estilo e durabilidade.',
                'price' => 399.90, 'stock' => 14, 'category' => 'feminino', 'active' => true,
            ],

            // -------------------------
            // INFANTIL
            // -------------------------
            [
                'name' => 'Nike Court Borough Low Kids',
                'description' => 'Estilo basquete para os pequenos, confortável e resistente.',
                'price' => 279.90, 'stock' => 25, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Adidas Superstar Kids',
                'description' => 'O clássico com biqueira shell toe em versão infantil.',
                'price' => 299.90, 'stock' => 20, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Converse All Star Kids',
                'description' => 'Chuck Taylor para crianças: leve, estiloso e versátil.',
                'price' => 199.90, 'stock' => 35, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Vans Old Skool Kids',
                'description' => 'O clássico Old Skool adaptado para o dia a dia das crianças.',
                'price' => 239.90, 'stock' => 18, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Puma Carina Kids',
                'description' => 'Estilo casual e conforto para a rotina escolar.',
                'price' => 229.90, 'stock' => 22, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Skechers D\'Lites Kids',
                'description' => 'Chunky confortável e com visual divertido.',
                'price' => 229.90, 'stock' => 28, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Olympikus Kids Jump',
                'description' => 'Leve e resistente para crianças ativas.',
                'price' => 149.90, 'stock' => 40, 'category' => 'infantil', 'active' => true,
            ],
            [
                'name' => 'Jordan 1 Mid Kids',
                'description' => 'Versão infantil do clássico Jordan 1 Mid. Estilo de sobra.',
                'price' => 699.90, 'stock' => 8, 'category' => 'infantil', 'active' => true,
            ],
        ];

        // Alguns itens em promoção + no carousel (somente se colunas existirem)
        if ($hasPromoColumns) {
            $promo = [
                [
                    'name' => 'Nike Air Max 90',
                    'discount_percentage' => 25,
                    'promotion_start' => $now->copy()->subDays(1),
                    'promotion_end' => $now->copy()->addDays(10),
                    'featured_in_carousel' => true,
                ],
                [
                    'name' => 'Adidas Samba OG',
                    'discount_percentage' => 18,
                    'promotion_start' => $now->copy()->subDays(2),
                    'promotion_end' => $now->copy()->addDays(7),
                    'featured_in_carousel' => true,
                ],
                [
                    'name' => 'New Balance 574',
                    'discount_percentage' => 15,
                    'promotion_start' => $now->copy()->subDays(1),
                    'promotion_end' => $now->copy()->addDays(5),
                    'featured_in_carousel' => true,
                ],
                [
                    'name' => 'Fila Disruptor II',
                    'discount_percentage' => 30,
                    'promotion_start' => $now->copy()->subDays(1),
                    'promotion_end' => $now->copy()->addDays(14),
                    'featured_in_carousel' => true,
                ],
                [
                    'name' => 'Converse All Star Kids',
                    'discount_percentage' => 20,
                    'promotion_start' => $now->copy()->subDays(1),
                    'promotion_end' => $now->copy()->addDays(9),
                    'featured_in_carousel' => true,
                ],
            ];

            // aplica promo nos produtos com mesmo name
            foreach ($products as &$p) {
                foreach ($promo as $pp) {
                    if ($p['name'] === $pp['name']) {
                        $p = array_merge($p, $pp);
                        break;
                    }
                }
            }
            unset($p);
        }

        // Se você quiser imagens reais depois, pode subir via Filament.
        // Por enquanto, deixa image null e cai no placeholder.
        foreach ($products as $product) {
            $product['image'] = $product['image'] ?? null;
            Product::create($product);
        }
    }
}
