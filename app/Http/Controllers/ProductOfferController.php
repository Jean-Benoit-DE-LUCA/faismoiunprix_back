<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product_Offer;
use App\Models\OfferNegotiate;

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

            /*return json_encode([
                "test" => $request->all()
            ]);*/

            $objProductOffer = new Product_Offer();
            $updateProdOffOfferAccecpted = $objProductOffer->updateProdOffOfferAccepted($request->get('offer_accepted'), $request->get('product_id'), $request->get('offer_id'));

            if ($request->get('offer_accepted') == 2 && $request->has('negotiate_price')) {
                
                $objOfferNegotiate = new OfferNegotiate();
                $insertOfferNegotiate = $objOfferNegotiate->insertOfferNegotiate(
                    $request->get('negotiate_price'),
                    $request->get('offer_id'),
                    $request->get('user_id')
                );
            }

            return json_encode([
                'flag' => true
            ]);
        }
    }
}
