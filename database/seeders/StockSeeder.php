<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['stock' => 75, 'product_id' => 1],
            ['stock' => 76, 'product_id' => 2],
            ['stock' => 80, 'product_id' => 3],
            ['stock' => 70, 'product_id' => 4],
            ['stock' => 75, 'product_id' => 5],
        ];

        foreach ($datas as $data) {
            Stock::create($data);
        }
    }
}
