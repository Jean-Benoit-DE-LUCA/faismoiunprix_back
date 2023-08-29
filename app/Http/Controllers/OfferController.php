<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class OfferController extends Controller
{
    public function insertOffer(Request $request) {

        $headers = getallheaders();

        if (array_key_exists('Authorization', $headers)) {

            if (preg_match('/Bearer/', $headers['Authorization'], $matches)) {

                require '../config.php';
                require_once('../vendor/autoload.php');

                try {
                    $token = JWT::decode(str_replace('Bearer ', '', $headers['Authorization']), new Key($data['JWT_SECRET_KEY'], 'HS256'));

                    $insertOffer = DB::insert('INSERT INTO `offers` (offer_submitted, user_id, product_id, offer_accepted) VALUES (?, ?, ?, ?)', [
                        htmlspecialchars($request->get('offer_submitted')),
                        $request->get('user_id'),
                        $request->get('product_id'),
                        $request->get('offer_accepted')
                    ]);

                    return json_encode([
                        "response" => true
                    ]);
                }

                catch (\Exception $e) {

                    return json_encode([
                        "response" => false
                    ]);
                }
            }
        }
    }
}
