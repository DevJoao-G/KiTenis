<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Brand::truncate();
        Schema::enableForeignKeyConstraints();

        $brands = [
            ['name' => 'Nike', 'is_active' => true],
            ['name' => 'Adidas', 'is_active' => true],
            ['name' => 'Puma', 'is_active' => true],
            ['name' => 'Vans', 'is_active' => true],
            ['name' => 'Converse', 'is_active' => true],
            ['name' => 'New Balance', 'is_active' => true],
            ['name' => 'Asics', 'is_active' => true],
            ['name' => 'Reebok', 'is_active' => true],
            ['name' => 'Mizuno', 'is_active' => true],
            ['name' => 'Fila', 'is_active' => true],
            ['name' => 'Olympikus', 'is_active' => true],
            ['name' => 'Skechers', 'is_active' => true],
            ['name' => 'Under Armour', 'is_active' => true],
            ['name' => 'Jordan', 'is_active' => true],
        ];

        Brand::insert($brands);
    }
}
