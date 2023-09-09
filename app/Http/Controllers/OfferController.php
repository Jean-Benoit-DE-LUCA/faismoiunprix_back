<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product_Offer;

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
                    
                    $objOffer = new Offer();

                    // return lastInsertId //
                    $insertOff = $objOffer->insertOff($request->get('offer_submitted'), $request->get('user_id'), $request->get('product_id'));
                    //

                    $objProductOffer = new Product_Offer();
                    $insertProdOff = $objProductOffer->insertProdOff($request->get('product_id'), $insertOff, $request->get('offer_accepted'));

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
