<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class OfferNegotiate extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_negotiate_submitted',
        'offer_id',
        'user_id'
    ];

    public function insertOfferNegotiate($offer_negotiate_submitted, $offer_id, $user_id) {

        $insertOfferNegotiate = DB::insert('INSERT INTO `offers_negotiate` (offer_negotiate_submitted, offer_id, user_id) VALUES (?, ?, ?);', [
            $offer_negotiate_submitted,
            $offer_id,
            $user_id
        ]);
    }

    public function deleteByOfferId($offer_id) {

        $delete = DB::delete('DELETE FROM `offers_negotiate` WHERE `offers_negotiate`.`offer_id` = ?', [$offer_id]);
    }
}
