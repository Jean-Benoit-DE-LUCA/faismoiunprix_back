<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_submitted',
        'user_id',
        'product_id'
    ];

    public function insertOff($offer_submitted, $user_id, $product_id) {
        
        $insert = DB::insert('INSERT INTO `offers` (offer_submitted, user_id, product_id) VALUES (?, ?, ?)', [
            $offer_submitted,
            $user_id,
            $product_id
        ]);

        return DB::getPdo()->lastInsertId();
    }

    public function getOffersSent($user_id) {

        $getOffersSent = DB::select('SELECT `users`.`user_name`, `users`.`user_firstname`, `offers`.*, `products`.`product_name`, `products`.`product_photos`
                                     FROM `offers` 
                                     INNER JOIN `users` ON `users`.`id` = `offers`.`user_id` 
                                     INNER JOIN `products` ON `products`.`id` = `offers`.`product_id` 
                                     WHERE `offers`.`user_id` = ?;', [$user_id]);
        return $getOffersSent;
    }

    public function getOffersReceived($user_id) {

        $getOffersReceived = DB::select('SELECT `offers`.`id` AS offerId, `offers`.`offer_submitted`, `offers`.`user_id` AS offerUserId, `offers`.`product_id` AS   offerProductId, `offers`.`created_at` AS offerCreatedAt, `offers`.`updated_at` AS offerUpdatedAt, `products`.`id` as productId, `products`.`product_name`, `products`.`created_at` as productCreatedAt, `products`.`updated_at` AS productUpdatedAt, `products`.`product_place`, `products`.`product_delivery`, `products`.`product_description`, `products`.`product_photos`, `products`.`user_id` as productUserId
        FROM offers
        INNER JOIN `products` on `products`.`id` = `offers`.`product_id`
        WHERE `products`.`user_id` = ?
        ORDER BY `offers`.`created_at` DESC;', [$user_id]);

        return $getOffersReceived;
    }

    public function getOfferId($offer_id) {

        $getOfferId = DB::select('SELECT `offers`.`id` AS offerId, `offers`.`offer_submitted`, `offers`.`user_id` AS offerUserId, `users`.`user_firstname` AS userFirstName, `offers`.`product_id` AS offerProductId, `products_offers`.`product_id` AS productOfferProductId, `products_offers`.`offer_id` AS productOfferOfferId, `products_offers`.`offer_accepted`, `products`.`id` AS productId, `products`.`product_name`, `products`.`created_at` AS productCreatedAt, `products`.`updated_at` AS productUpdatedAt, `products`.`product_place`, `products`.`product_delivery`, `products`.`product_description`, `products`.`product_photos`, `products`.`user_id` AS productUserId 
        FROM `offers` 
        INNER JOIN `products_offers` ON `products_offers`.`offer_id` = `offers`.`id` 
        INNER JOIN `products` ON `products`.`id` = `offers`.`product_id`
        INNER JOIN `users` ON `users`.`id` = `offers`.`user_id` 
        WHERE `offers`.`id` = ?;', [$offer_id]);

        return $getOfferId;
    }
}
