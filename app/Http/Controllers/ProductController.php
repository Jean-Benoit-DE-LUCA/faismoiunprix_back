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
                        $delivery
                    );
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
}
