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
}