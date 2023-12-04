<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Offer;
use App\Models\OfferMessage;
use App\Models\OfferNegotiate;
use App\Models\Product_Offer;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ProductController extends Controller
{

    public function getProducts() {

        $productObj = new Product();
        $getProducts = $productObj->selectProducts();
        return json_encode($getProducts);
    }

    public function getMyProducts($user_id) {

        $productObj = new Product();
        $getMyProducts = $productObj->selectMyProducts($user_id);
        return json_encode($getMyProducts);
    }

    public function getProductById($product_id) {

        $productObj = new Product();
        $getSpecificProduct = $productObj->getSpecificProduct($product_id);

        $getSpecificProductFiltered = [];

        foreach ($getSpecificProduct[0] as $key => $value) {

            if ($key == 'product_name' || $key == 'product_description' || $key == 'product_place') {

                $getSpecificProductFiltered[0][$key] = htmlspecialchars_decode($value);
            }

            else {

                $getSpecificProductFiltered[0][$key] = $value;
            }
        }

        return json_encode([
            'productFound' => $getSpecificProductFiltered
        ]);
    }

    public function insertProduct(Request $request) {

        if (JWTController::checkJWT()) {

            if ($request->get('delivery_product') == 'yes') {
                $delivery = 1;
            }

            else if ($request->get('delivery_product') == 'no') {
                $delivery = 0;
            }

            $productObj = new Product();
            $addProduct = $productObj->addProduct(
                htmlspecialchars($request->get('name_product')),
                htmlspecialchars($request->get('description_product')),
                htmlspecialchars($request->get('place_product')),
                $delivery,
                $request->get('user_id')
            );

            return json_encode([
                "response" => true,
                "lastInsertId" => $addProduct
            ]);
        }

        else {

            return json_encode([
                "response" => false
            ]);
        }
    }

    public function insertProductImagesFolder(Request $request) {

        $arrayImages = [];
        $countImages = 0;

        foreach ($request->all() as $key => $value) {

            if (str_starts_with($key, "image")) {

                $countImages++;

                $arrayImages[$key] = [
                    $request->file($key)->getClientOriginalName(),
                    $request->file($key)->getPathName(),
                    $request->file($key)->getClientOriginalExtension(),
                    $request->file($key)->getSize(),
                    time() . '_' . $request->file($key)->getClientOriginalName()
                ];
                $request->file($key)->move('assets/images', time() . '_' . $request->file($key)->getClientOriginalName());
            }
        }

        if ($countImages > 0) {

            $last_insert_id = str_replace('/api/insertproductimages/folder/', '', $_SERVER['PATH_INFO']);
            $arrayImagesNames = [];

            foreach ($arrayImages as $key => $value) {

                array_push($arrayImagesNames, $value[count($value) - 1]);
            }

            $this->updateProductImagesDatabase($last_insert_id, implode(',', $arrayImagesNames));
        }
    }

    public function updateProductImagesDatabase ($last_insert_id, $stringArrayImagesNames) {

        $productObj = new Product();
        $updateImagesDatabase = $productObj->updateImagesDatabase($last_insert_id, $stringArrayImagesNames);
    }



    public function updateProduct(Request $request, $product_id) {

        if (JWTController::checkJWT()) {

            $flag = null;

            try {
    
                // SEPARATE FILE NAMES AND FILE OBJECT //
    
                $newArrayFileNames = [];
                $newArrayFiles = [];
    
                foreach ($request->all() as $key => $element) {
    
                    if (str_starts_with($key, '_inp_input--')) {
                        $newArrayFileNames[$key] = $element;
                    }
    
                    else if (gettype($element) == 'object') {
                        $newArrayFiles[$key] = $element;
                    }
                }
    
    
    
    
                // CLEAR NAMES ARRAY //
    
                $newArrayFileNamesNew = [];
    
                foreach ($newArrayFileNames as $key => $fileName) {
    
                    if (str_contains($fileName, '/images/no_image.png')) {
                        $newArrayFileNamesNew[$key] = 'no_image';
                    }
    
                    else if (str_contains($fileName, 'http://127.0.0.1:8000/assets/images/')) {
                        $newArrayFileNamesNew[$key] = str_replace('http://127.0.0.1:8000/assets/images/', '', $fileName);
                    }
    
                    else if (str_contains($fileName, 'data:image/')) {
                        $keys = array_keys($newArrayFiles);
    
                        for ($i = 0; $i < count($keys); $i++) {
    
                            if (str_contains($keys[$i], $key)) {
                                $newArrayFileNamesNew[$key] = strrev(substr_replace(
                                    strrev(time() . '_' . trim(str_replace($key, '', $keys[$i]))),
                                    '.',
                                    strpos(strrev(time() . '_' . trim(str_replace($key, '', $keys[$i]))), '_'),
                                    1
                                ));
                            }
                        }
                    }
                }
    
    
    
    
                // GET PREPARED ARRAY IMPLODE TO INSERT WITH NEW PICTURES NAMES //
    
    
                $newArrayFileNamesToInsert = [];
    
                foreach ($newArrayFileNamesNew as $key => $value) {
    
                    if ($newArrayFileNamesNew[$key] == 'no_image') {
                        unset($newArrayFileNamesNew[$key]);
                    }
    
                    else {
                        $newArrayFileNamesToInsert[] = $newArrayFileNamesNew[$key];
                    }
                }
    
    
    
    
                // RENAME KEY NAME FILES ARRAY //
    
                $renameArray = [];
    
                foreach ($newArrayFileNamesNew as $key => $value) {
    
                    foreach ($newArrayFiles as $keyFile => $valueFile) {
    
                        if (str_contains($keyFile, $key)) {
    
                            $renameArray[$value] = $valueFile;
                        }
                    }
                }
    
    
    
    
                // UPLOAD NEW FILES //
    
                $arrayExtensions = ['jpg', 'jpeg', 'bmp', 'gif', 'png', 'svg'];
    
                $arraySize = [];
    
                foreach ($renameArray as $key => $value) {
    
                    if ($value->getSize() < 5000000) {
    
                        if (in_array($value->getClientOriginalExtension(), $arrayExtensions)) {
    
                            $arraySize[] = $key;
                            $value->move('assets/images', strval($key));
                        }
                    }
                }
    
    
    
    
                // RETRIEVE OLD IMAGES ARRAY FROM DATABASE //
    
                $objProduct = new Product();
                $getProduct = $objProduct->getSpecificProduct($product_id);
    
                $arrayFileNamesOld = explode(',', $getProduct[0]->product_photos);
    
    
    
    
                // DELETE OLD NOT USED IMAGES FROM SERVER //
    
                foreach ($arrayFileNamesOld as $fileName) {
    
                    if (!in_array($fileName, $newArrayFileNamesToInsert)) {
    
                        unlink(dirname(dirname(dirname(dirname(__FILE__)))) . '/public/assets/images/' . $fileName);
                    }
                }
    
    
    
    
                // UPDAPTE DATABASE PRODUCTS.PRODUCT_PHOTOS WITH NEW IMAGE NAMES //
    
                $this->updateProductImagesDatabase($product_id, implode(',', $newArrayFileNamesToInsert));
    
    
    
                // UPDATE TITLE, DESCRIPTION, PLACE, DELIVERY (TRUE/FALSE) //
    
                $product_name = htmlspecialchars($request->get('product_name'));
                $product_description = htmlspecialchars($request->get('product_description'));
                $product_place = htmlspecialchars($request->get('product_place'));
                $product_delivery = null;
    
                if ($request->get('product_delivery') == 'true') {
                    $product_delivery = 1;
                }
                else {
                    $product_delivery = 0;
                }
    
                $this->updateProductPropertiesExceptPictures($product_id, $product_name, $product_description, $product_place, $product_delivery);
                
    
                $flag = true;
    
            }
    
            catch (\Exception $e) {
    
                $flag = false;
            }
    
    
            return json_encode([
                //'arrayFileNames' => $newArrayFileNames,
                //'arrayFiles' => $newArrayFiles,
                /*'newArrayFileNamesNew' => $newArrayFileNamesNew,
                'arrayFileNamesToInsert' => implode(',', $newArrayFileNamesToInsert),
                'arrayFileNamesToInsertArray' => $newArrayFileNamesToInsert,
                'product_id' => $product_id,
                'renameArray' => $renameArray,
                'arrayProduct' => $getProduct,
                'arrayFileNamesOld' => $arrayFileNamesOld,
                'arraySize' => $arraySize,
                'arr' => $array*/
                /*'pname' => $product_name,
                'pdesc' => $product_description,
                'pplace' => $product_place,
                'pddeli' => $product_delivery*/
                'flag' => $flag
            ]);
        }

        else {

            return json_encode([
                'error' => 'Erreur d\'authentification'
            ]);
        }

    }

    public function updateProductPropertiesExceptPictures($product_id, $product_name, $product_description, $product_place, $product_delivery) {

        $productObj = new Product();
        $updateOthersProperties = $productObj->updateProductExceptPictures($product_id, $product_name, $product_description, $product_place, $product_delivery);
    }

    public function deleteProduct($product_id) {

        $productObj = new Product();
        $offerObj = new Offer();
        $offerMessageObj = new OfferMessage();
        $offerNegotiateObj = new OfferNegotiate();
        $productOfferObj = new Product_Offer();

        $flag = null;

        try {

            // DELETE PRODUCT //

            $productObj->deleteProduct($product_id);

            // GET ALL OFFER IDS BY PRODUCT AND -> DELETE EACH MESSAGE BY OFFER -> DELETE EACH OFFER NEGOTIATE BY OFFER //

            $getOffersByProduct = $offerObj->getOffersByProductId($product_id);

            $getOffersByProductFiltered = [];
            foreach($getOffersByProduct as $key => $value) {

                $getOffersByProductFiltered[] = $value->id;
            }


            foreach($getOffersByProductFiltered as $key => $value) {

                $offerNegotiateObj->deleteByOfferId($value);
                $productOfferObj->deleteProductOfferByOfferId($value);

                $getMessages = $offerMessageObj->getOffMessById($value);

                foreach($getMessages as $key => $value) {

                    $offerMessageObj->deleteOffMess($value->id);
                }
            }

            // DELETE OFFERS //

            $offerObj->deleteOffers($product_id);

            $flag = true;
        }
        
        catch (Exception $e) {

            $flag = false;
        }

        return json_encode([
            'flag' => $flag
        ]);
    }
}
