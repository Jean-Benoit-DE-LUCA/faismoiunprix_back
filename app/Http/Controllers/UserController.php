<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        if (Hash::check($request->input('user_password'), $getUser[0]->user_password)) {

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
                'user_firstname' => $getUser[0]->user_firstname,
                'user_address' => $getUser[0]->user_address,
                'user_zip' => $getUser[0]->user_zip,
                'user_phone' => $getUser[0]->user_phone,
                'user_id' => $getUser[0]->id,
                'user_role' => $getUser[0]->user_role,
                'jwt' => $jwt
            ]);
        }

        else if (!Hash::check($request->input('user_password'), $getUser[0]->user_password)) {
            return json_encode([
                'userPass' => false
            ]);
        }
    }

    public function registerUser(Request $request) {

        $requestExtract = extract($request->all());

        $userObj = new User();
        $getUser = $userObj->selectUserMail($email);

        $message = "";
        $flag = NULL;

        if (count($getUser) !== 0) {

            $message = 'Email déjà enregistré';
            $flag = false;

            return json_encode([
                'message' => $message,
                'flag' => $flag
            ]);
        }

        else if (count($getUser) == 0) {

            if ($password == $password_confirmation) {

                require '../config.php';
                require_once('../vendor/autoload.php');
    
                $secret_key = $data['JWT_SECRET_KEY'];
                $time = time();
                $expire_at = time() + 1200;
                $server_name = $_SERVER["SERVER_NAME"];
                //$user_name = $getUser[0]->user_name;
    
                $payload = [
                    'iss' => $server_name,
                    'aud' => $server_name,
                    'iat' => $time,
                    'nbf' => $time,
                    'exp' => $expire_at
                ];
    
                $jwt = JWT::encode($payload, $secret_key, 'HS256');
                
                $insertUser = $userObj->insertUser($name, $firstName, $email, $address, $zip, $phone, Hash::make($password), 'member');

                $message = 'Inscription effectuée avec succès';
                $getInsertedUser = $userObj->selectUserMail($email);
                $flag = true;

                return json_encode([
                    'message' => $message,
                    'user' => $getInsertedUser,
                    'jwt' => $jwt,
                    'flag' => $flag
                ]);
            }
        }
    }
}
