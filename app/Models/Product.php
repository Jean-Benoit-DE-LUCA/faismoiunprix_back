<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'product_description',
        'product_place',
        'product_delivery'
    ];

    public function selectProducts() {

        $selectProducts = DB::select('SELECT * FROM `products`');
        return $selectProducts;
    }

    public function addProduct($product_name, $product_description, $product_place, $product_delivery) {

        $insert = DB::insert('INSERT INTO `products` (product_name, product_description, product_place, product_delivery) VALUES (?, ?, ?, ?)', [
            $product_name,
            $product_description,
            $product_place,
            $product_delivery
        ]);
    }
}
