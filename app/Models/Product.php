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
        'product_photos',
        'user_id'
    ];

    public function selectProducts() {

        $selectProducts = DB::select('SELECT *
                                    FROM `products`
                                    WHERE `products`.`id`
                                    NOT IN
                                    (SELECT DISTINCT `products`.`id`
                                    FROM `products` 
                                    INNER JOIN `products_offers` ON `products_offers`.`product_id` = `products`.`id`
                                    WHERE `products_offers`.`offer_accepted` = 1);');

        return $selectProducts;
    }

    public function selectMyProducts($user_id) {

        $selectMyProducts = DB::select('SELECT * FROM `products` WHERE `products`.`user_id` = ?', [$user_id]);

        return $selectMyProducts;
    }

    public function getSpecificProduct($id) {

        $getProductById = DB::select('SELECT * FROM `products` WHERE `products`.`id` = ?', [$id]);
        return $getProductById;
    }

    public function addProduct($product_name, $product_description, $product_place, $product_delivery, $user_id) {

        $insert = DB::insert('INSERT INTO `products` (product_name, product_description, product_place, product_delivery, user_id) VALUES (?, ?, ?, ?, ?)', [
            $product_name,
            $product_description,
            $product_place,
            $product_delivery,
            $user_id
        ]);
        return DB::getPdo()->lastInsertId();
    }

    public function updateImagesDatabase($last_insert_id, $stringArrayImagesNames) {

        $updateProductImages = DB::update('UPDATE `products` SET `products`.`product_photos` = ? WHERE `products`.`id` = ?', [$stringArrayImagesNames, $last_insert_id]);
    }

    public function updateProductExceptPictures($product_id, $product_name, $product_description, $product_place, $product_delivery) {

        $update = DB::update('UPDATE `products` SET `products`.`product_name` = ?, `products`.`product_description` = ?, `products`.`product_place` = ?, `products`.`product_delivery` = ? WHERE `products`.`id` = ?', [
            $product_name,
            $product_description,
            $product_place,
            $product_delivery,
            $product_id
        ]);
    }

    public function deleteProduct($product_id) {

        $delete = DB::delete('DELETE FROM `products` WHERE `products`.`id` = ?', [$product_id]);
    }
}
