<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;


class Telegram
{

    static function checkTelegramAuthorization($auth_data)
    {
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', env('TELEGRAM_BOT_TOKEN'), true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            return false;
        }
        if ((time() - $auth_data['auth_date']) > 86400) {

            return false;
        }
        return true;
    }

    static function getUser($tg_user)
    {


        $user = User::query()->where("telegram_id", $tg_user['id'])->first();
        if (!$user) {
            $user = new User();
        }
        if (isset($user->last_id) and $user->enable == 0) {
            return false;
        }


        $user->name = $tg_user['first_name'];
        if (isset($tg_user['last_name'])) {
            $user->name = $user->name . " " . $tg_user['last_name'];
        }
        if (isset($tg_user['username'])) {
            $user->username = $tg_user['username'];
        }
        $user->telegram_id = $tg_user['id'];
        $user->api_token = \Illuminate\Support\Str::random(80);
        if (isset($tg_user['photo_url'])) {
            $contents = file_get_contents($tg_user['photo_url']);

// Save the variable as `google.html` file onto
// your local drive, most probably at `your_laravel_project/storage/app/`
// path (as per default Laravel storage config)
            Storage::disk('elfinder')->put('tmpfiles/avatars/avatar_' . $tg_user['id'] . ".jpg", $contents);
            $user->avatar = '/files/tmpfiles/avatars/avatar_' . $tg_user['id'] . ".jpg";
        }

        if (is_null($user->avatar)) {
            $user->avatar = '/files/tmpfiles/default-avatar.png';
        }
        $user->enable = 1;


        $user->save();
        $user->makeVisible("api_token");
        return $user;
    }


}