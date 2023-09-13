<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTController extends Controller
{
    public static function checkJWT() {
        $headers = getallheaders();

        if (array_key_exists('Authorization', $headers)) {

            if (preg_match('/Bearer/', $headers['Authorization'], $matches)) {

                require '../config.php';
                require_once('../vendor/autoload.php');

                $flag = false;

                try {
                    $token = JWT::decode(str_replace('Bearer ', '', $headers['Authorization']), new Key($data['JWT_SECRET_KEY'], 'HS256'));
                    $flag = true;
                }

                catch (\Exception $e) {
                    $flag = false;
                }

                return $flag;
            }
        }
    }
}
