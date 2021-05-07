<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Telegram;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class UserController extends Controller
{


    public function Login()
    {


        $post = request()->post();
        if (!(isset($post['auth_date']) and isset($post['first_name']) and isset($post['hash'])
            and isset($post['id']))) {

            return array('type' => 'error', 'message' => 'Авторизация не удалась.Попробуйте указать Имя в Telegram');
        }


        $result = Telegram::checkTelegramAuthorization($post);


        if (!$result) {
            return array('type' => 'error', 'message' => 'Переданные данные невалидные. Попробуйте еще раз.');

        }


        if ($result) {

            $user = Telegram::getUser($post);


            if ($user === false) {
                return array('type' => 'error', 'message' => 'Вы забанены!');
            }


            return array('type' => 'success', 'user' => $user);
        }


    }

}