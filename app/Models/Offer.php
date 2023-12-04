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

        $getOffersSent = DB::select('SELECT `users`.`user_name`, `users`.`user_firstname`,
                                    `offers`.`id` as offerId, `offers`.`offer_submitted`, `offers`.`user_id`, `offers`.`product_id`, `offers`.`created_at`, `offers`.`updated_at`, 
                                    `products`.`product_name`, `products`.`product_photos`, `products_offers`.`offer_accepted`
                                    FROM `offers` 
                                    INNER JOIN `users` ON `users`.`id` = `offers`.`user_id` 
                                    INNER JOIN `products` ON `products`.`id` = `offers`.`product_id`
                                    INNER JOIN `products_offers` ON `products_offers`.`offer_id` = `offers`.`id`
                                    WHERE `offers`.`user_id` = ?;', [$user_id]);
        return $getOffersSent;
    }

    public function getOffersReceived($user_id) {

        $getOffersReceived = DB::select('SELECT `offers`.`id` AS offerId, `offers`.`offer_submitted`, `offers`.`user_id` AS offerUserId, `offers`.`product_id` AS   offerProductId, `offers`.`created_at` AS offerCreatedAt, `offers`.`updated_at` AS offerUpdatedAt, `products`.`id` as productId, `products`.`product_name`, `products`.`created_at` as productCreatedAt, `products`.`updated_at` AS productUpdatedAt, `products`.`product_place`, `products`.`product_delivery`, `products`.`product_description`, `products`.`product_photos`, `products`.`user_id` as productUserId,
        `products_offers`.`offer_accepted`
                FROM offers
                INNER JOIN `products` on `products`.`id` = `offers`.`product_id`
                INNER JOIN `products_offers` on `products_offers`.`offer_id` = `offers`.`id`
                WHERE `products`.`user_id` = ?
                ORDER BY `offers`.`created_at` DESC;', [$user_id]);

        return $getOffersReceived;
    }

    public function getOfferId($offer_id) {

        $getOfferIdNegotiate = DB::select('SELECT `offers`.`id` AS offerId, `offers`.`offer_submitted`, `offers`.`user_id` AS offerUserId, u1.`user_firstname` AS userFirstName, u2.`user_firstname` AS userProductName, `offers`.`product_id` AS offerProductId, `products_offers`.`product_id` AS productOfferProductId, `products_offers`.`offer_id` AS productOfferOfferId, `products_offers`.`offer_accepted`, `products`.`id` AS productId, `products`.`product_name`, `products`.`created_at` AS productCreatedAt, `products`.`updated_at` AS productUpdatedAt, `products`.`product_place`, `products`.`product_delivery`, `products`.`product_description`, `products`.`product_photos`, `products`.`user_id` AS productUserId,
        `offers_negotiate`.`offer_negotiate_submitted`, `offers_negotiate`.`user_id` as offerNegotiateUserId, u3.`user_firstname` AS userOfferNegotiateName, `offers_negotiate`.`created_at` AS offerNegotiateCreatedAt
                                        FROM `offers`
                                        INNER JOIN `products_offers` ON `products_offers`.`offer_id` = `offers`.`id` 
                                        INNER JOIN `products` ON `products`.`id` = `offers`.`product_id`
                                        INNER JOIN `users` AS u1 ON u1.`id` = `offers`.`user_id`
                                        INNER JOIN `users` AS u2 ON u2.`id` = `products`.`user_id`
                                        INNER JOIN `offers_negotiate` ON `offers_negotiate`.`offer_id` = `offers`.`id`
                                        INNER JOIN `users` AS u3 ON u3.`id` = `offers_negotiate`.`user_id`
                                        WHERE `offers`.`id` = ?
                                        ORDER BY offerNegotiateCreatedAt DESC
                                        LIMIT 1;', [$offer_id]);

        $getOfferId = DB::select('SELECT `offers`.`id` AS offerId, `offers`.`offer_submitted`, `offers`.`user_id` AS offerUserId, u1.`user_firstname` AS userFirstName, u2.`user_firstname` AS userProductName, `offers`.`product_id` AS offerProductId, `products_offers`.`product_id` AS productOfferProductId, `products_offers`.`offer_id` AS productOfferOfferId, `products_offers`.`offer_accepted`, `products`.`id` AS productId, `products`.`product_name`, `products`.`created_at` AS productCreatedAt, `products`.`updated_at` AS productUpdatedAt, `products`.`product_place`, `products`.`product_delivery`, `products`.`product_description`, `products`.`product_photos`, `products`.`user_id` AS productUserId
                                FROM `offers` 
                                INNER JOIN `products_offers` ON `products_offers`.`offer_id` = `offers`.`id` 
                                INNER JOIN `products` ON `products`.`id` = `offers`.`product_id`
                                INNER JOIN `users` AS u1 ON u1.`id` = `offers`.`user_id`
                                INNER JOIN `users` AS u2 ON u2.`id` = `products`.`user_id`
                                WHERE `offers`.`id` = ?;', [$offer_id]);

        if (count($getOfferIdNegotiate) > 0) {

            return $getOfferIdNegotiate;
        }

        return $getOfferId;
    }

    public function deleteOffers($product_id) {

        $delete = DB::delete('DELETE FROM `offers` WHERE `offers`.`product_id` = ?', [$product_id]);
    }

    public function getOffersByProductId($product_id) {

        $select = DB::select('SELECT * FROM `offers` WHERE `offers`.`product_id` = ?', [$product_id]);

        return $select;
    }
}
