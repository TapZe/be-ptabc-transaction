<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'starting_stock' => 100,
                'selled_stock' => 10,
                'transaction_date' => '2021-05-01',
                'product_id' => 1,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 100,
                'selled_stock' => 19,
                'transaction_date' => '2021-05-05',
                'product_id' => 2,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 90,
                'selled_stock' => 10,
                'transaction_date' => '2021-05-10',
                'product_id' => 1,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 100,
                'selled_stock' => 20,
                'transaction_date' => '2021-05-11',
                'product_id' => 3,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 100,
                'selled_stock' => 30,
                'transaction_date' => '2021-05-11',
                'product_id' => 4,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 100,
                'selled_stock' => 25,
                'transaction_date' => '2021-05-12',
                'product_id' => 5,
                'user_id' => 1,
            ],
            [
                'starting_stock' => 81,
                'selled_stock' => 5,
                'transaction_date' => '2021-05-12',
                'product_id' => 2,
                'user_id' => 1,
            ],
        ];

        foreach ($datas as $data) {
            Transaction::create($data);
        }
    }
}
