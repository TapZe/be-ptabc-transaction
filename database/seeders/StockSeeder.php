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
            ['quantity' => 75, 'product_id' => 1],
            ['quantity' => 76, 'product_id' => 2],
            ['quantity' => 80, 'product_id' => 3],
            ['quantity' => 70, 'product_id' => 4],
            ['quantity' => 75, 'product_id' => 5],
        ];

        foreach ($datas as $data) {
            Stock::create($data);
        }
    }
}
