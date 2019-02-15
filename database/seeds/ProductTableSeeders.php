<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'title' => 'Macbook Pro 15 2019',
            'description' => 'Laptop andalan dari Apple dengan Layar memukau',
            'price' => 18500000,
            'stock' => 5
        ]);

        Product::create([
            'title' => 'Asus ROG GL65VD',
            'description' => 'Laptop gaming kekinian masa kini',
            'price' => 16500000,
            'stock' => 7
        ]);

    }
}
