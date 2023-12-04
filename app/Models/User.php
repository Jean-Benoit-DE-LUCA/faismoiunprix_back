<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'user_firstname',
        'user_mail',
        'user_address',
        'user_zip',
        'user_city',
        'user_phone',
        'user_password',
        'user_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function selectUserMail($email) {
        $getUser = DB::select('SELECT * FROM `users` WHERE `users`.`user_mail` = ?', [$email]);
        return $getUser;
    }

    public function insertUser($name, $firstName, $email, $address, $zip, $city, $phone, $password, $role = 'member') {

        $insertUser = DB::insert('INSERT INTO `users` (user_name, user_firstname, user_mail, user_address, user_zip, user_city, user_phone, user_password, user_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$name, $firstName, $email, $address, $zip, $city, $phone, $password, $role]);
    }

    public function updateUser($user_id, $name, $firstName, $address, $zip, $city, $phone, $password) {

        $updateUser = DB::update('UPDATE `users` SET `users`.`user_name` = ?,
                                                     `users`.`user_firstname` = ?,
                                                     `users`.`user_address` = ?,
                                                     `users`.`user_zip` = ?,
                                                     `users`.`user_city` = ?,
                                                     `users`.`user_phone` = ?,
                                                     `users`.`user_password` = ?
                                                WHERE `users`.`id` = ?', [
                                                        $name,
                                                        $firstName,
                                                        $address,
                                                        $zip,
                                                        $city,
                                                        $phone,
                                                        $password,
                                                        $user_id
                                                     ]);
    }
}
