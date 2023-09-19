<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OfferMessage;
use App\Http\Controllers\JWTController;

class OfferMessageController extends Controller
{
    public function insertOfferMessage(Request $request) {

        if (JWTController::checkJWT()) {

                $offerMessObj = new OfferMessage();
                $insertOffMess = $offerMessObj->insertOffMess($request->get('offer_id'), $request->get('username_message'), $request->get('content_message'));

                return json_encode([
                    'messageInsert' => 'inserted'
                ]);
            }


        else if (!JWTController::checkJWT()) {
            
            return json_encode([
                'error' => 'Erreur d\'authentification'
            ]);
        }

    }

    public function getOffersMessagesById(Request $request, $offer_id) {

        if (JWTController::checkJWT()) {

            $offerMessObj = new OfferMessage();
            $getOffMessById = $offerMessObj->getOffMessById($offer_id);

            $arrayResult = [];

            foreach ($getOffMessById as $key => $obj) {

                $newArray = [];

                foreach ($obj as $keyName => $value) {

                    if ($keyName !== 'content_message') {
                        $newArray[$keyName] = $value;
                    }
                    
                    else {
                        $newArray[$keyName] = htmlspecialchars_decode($value);
                    }
                }

                $arrayResult[] = $newArray;
            }

            return json_encode([
                'messagesByOfferId' => $arrayResult
            ]);
        }
    }
}
