<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function getUser(Request $request, $user_mail) {
        $userObj = new User();
        $getUser = $userObj->selectUserMail($user_mail);

        if (count($getUser) == 0) {
            return json_encode([
                'userFound' => false
            ]);
        }

        if ($getUser[0]->user_password == $request->input('user_password')) {

            require '../config.php';
            require_once('../vendor/autoload.php');

            $secret_key = $data['JWT_SECRET_KEY'];
            $time = time();
            $expire_at = time() + 1200;
            $server_name = $_SERVER["SERVER_NAME"];
            $user_name = $getUser[0]->user_name;

            $payload = [
                'iss' => $server_name,
                'aud' => $server_name,
                'iat' => $time,
                'nbf' => $time,
                'exp' => $expire_at
            ];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            return json_encode([
                'user_mail' => $getUser[0]->user_mail,
                'user_name' => $getUser[0]->user_name,
                'user_id' => $getUser[0]->id,
                'user_role' => $getUser[0]->user_role,
                'jwt' => $jwt
            ]);
        }

        else if ($getUser[0]->user_password !== $request->input('user_password')) {
            return json_encode([
                'userPass' => false
            ]);
        }
    }
}
