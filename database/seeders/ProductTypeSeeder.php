<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'Konsumsi'],
            ['name' => 'Pembersih'],
        ];

        foreach ($datas as $data) {
            ProductType::create($data);
        }
    }
}
