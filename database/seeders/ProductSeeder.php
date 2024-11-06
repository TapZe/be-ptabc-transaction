<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'Kopi', 'product_type_id' => 1],
            ['name' => 'Teh', 'product_type_id' => 1],
            ['name' => 'Pasta Gigi', 'product_type_id' => 2],
            ['name' => 'Sabun Mandi', 'product_type_id' => 2],
            ['name' => 'Sampo', 'product_type_id' => 2],
        ];

        foreach ($datas as $data) {
            Product::create($data);
        }
    }
}
