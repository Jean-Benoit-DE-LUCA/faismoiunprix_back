<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ProductController extends Controller
{
    public function getProducts() {

        $productObj = new Product();
        $getProducts = $productObj->selectProducts();
        return json_encode($getProducts);
    }

    public function getProductById($product_id) {

        $productObj = new Product();
        $getSpecificProduct = $productObj->getSpecificProduct($product_id);

        return json_encode([
            'productFound' => $getSpecificProduct
        ]);
    }

    public function insertProduct(Request $request) {

        $headers = getallheaders();

        if (array_key_exists('Authorization', $headers)) {

            if (preg_match('/Bearer/', $headers['Authorization'], $matches)) {

                require '../config.php';
                require_once('../vendor/autoload.php');

                $flag = true;

                try {

                    $token = JWT::decode(str_replace('Bearer ', '', $headers['Authorization']), new Key($data['JWT_SECRET_KEY'], 'HS256'));
                    $flag = true;

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
                        "response" => $flag,
                        "lastInsertId" => $addProduct
                    ]);
                }

                catch (\Exception $e) {

                    $flag = false;
                }

                return json_encode([
                    "response" => $flag
                ]);
                
            }
            
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
}
