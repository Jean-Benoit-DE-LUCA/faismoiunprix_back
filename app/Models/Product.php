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
        'product_delivery',
        'product_photos'
    ];

    public function selectProducts() {

        $selectProducts = DB::select('SELECT * FROM `products`');
        return $selectProducts;
    }

    public function getSpecificProduct($id) {

        $getProductById = DB::select('SELECT * FROM `products` WHERE `products`.`id` = ?', [$id]);
        return $getProductById;
    }

    public function addProduct($product_name, $product_description, $product_place, $product_delivery) {

        $insert = DB::insert('INSERT INTO `products` (product_name, product_description, product_place, product_delivery) VALUES (?, ?, ?, ?)', [
            $product_name,
            $product_description,
            $product_place,
            $product_delivery
        ]);
        return DB::getPdo()->lastInsertId();
    }

    public function updateImagesDatabase($last_insert_id, $stringArrayImagesNames) {

        $updateProductImages = DB::update('UPDATE `products` SET `products`.`product_photos` = ? WHERE `products`.`id` = ?', [$stringArrayImagesNames, $last_insert_id]);
    }
}
