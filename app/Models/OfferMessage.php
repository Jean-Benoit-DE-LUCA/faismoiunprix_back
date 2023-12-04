<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class OfferMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'username_message',
        'content_message'
    ];

    public function insertOffMess($offer_id, $username_message, $content_message) {
        
        $insertOfferMess = DB::insert('INSERT INTO `offers_messages` (offer_id, username_message, content_message) VALUES (?, ?, ?)', [
            $offer_id,
            $username_message,
            htmlspecialchars($content_message)
        ]);
    }

    public function getOffMessById($offer_id) {

        $getOffMessById = DB::select('SELECT * FROM `offers_messages` WHERE `offers_messages`.`offer_id` = ? ORDER BY `offers_messages`.`created_at` ASC;', [
            $offer_id
        ]);

        return $getOffMessById;
    }

    public function deleteOffMess($id) {

        $delete = DB::delete('DELETE FROM `offers_messages` WHERE `offers_messages`.`id` = ?', [$id]);
    }
}
