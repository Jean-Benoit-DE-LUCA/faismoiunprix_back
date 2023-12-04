<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Product_Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'offer_id',
        'offer_accepted'
    ];

    public function insertProdOff($product_id, $offer_id, $offer_accepted) {

        $insertProdOff = DB::insert('INSERT INTO `products_offers` (product_id, offer_id, offer_accepted) VALUES (?, ?, ?)', [
            $product_id,
            $offer_id,
            $offer_accepted
        ]);
    }

    public function updateProdOffOfferAccepted($offer_accepted, $product_id, $offer_id) {

        $updateProdOffAccept = DB::update('UPDATE `products_offers` SET `products_offers`.`offer_accepted` = ? WHERE `products_offers`.`product_id` = ? AND `products_offers`.`offer_id` = ?', [$offer_accepted, $product_id, $offer_id]);
    }

    public function deleteProductOfferByOfferId($offer_id) {

        $delete = DB::delete('DELETE FROM `products_offers` WHERE `products_offers`.`offer_id` = ?', [$offer_id]);
    }
}
