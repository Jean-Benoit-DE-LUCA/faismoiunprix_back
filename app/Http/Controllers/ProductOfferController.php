<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product_Offer;

class ProductOfferController extends Controller
{
    //

    protected $fillable = [
        'product_id',
        'offer_id',
        'offer_accepted'
    ];

    public function updateProductOfferOfferAccepted(Request $request) {

        if (JWTController::checkJWT()) {

            $objProductOffer = new Product_Offer();
            $updateProdOffOfferAccecpted = $objProductOffer->updateProdOffOfferAccepted(1, $request->get('product_id'), $request->get('offer_id'));

            return json_encode([
                'flag' => true
            ]);
        }
    }
}
