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
}
