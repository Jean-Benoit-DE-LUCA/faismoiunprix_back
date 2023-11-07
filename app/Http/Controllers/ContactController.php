<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendContactMail(Request $request) {

        require_once "../vendor/autoload.php";
        include "../config.php";

        $lastName = htmlspecialchars($request->get('lastName'));
        $firstName = htmlspecialchars($request->get('firstName'));
        $email = htmlspecialchars($request->get('email'));
        $message = htmlspecialchars($request->get('message'));

        try {

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = $data['email'];
            $mail->Password = $data['email_password'];
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';

            $mail->setFrom($email, 'Contact');
            $mail->addAddress($data['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Contact message "Fais moi un prix!"';
            $mail->Body = $message . '<br>Message de: ' . $lastName . ' ' . $firstName . '<br>Contact: ' . $email;

            $mail->send();

            return json_encode([
                "success" => "Message sent!"
            ]);

        }

        catch(Exception $e) {
            return json_encode([
                "error" => $e
            ]);
        }
    }
}
