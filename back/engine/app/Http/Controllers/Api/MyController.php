<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class MyController extends Controller
{

    public function setAccount()
    {
        $body = request()->post();

        if (!(isset($body['account']) and is_string($body['account'])) and strlen($body['account']) > 0) {

            return array('type' => 'error', 'message' => 'Должна быть передана строка');
        }

        $user = request()->user();
        $user->account = strip_tags($body['account']);
        $user->save();
        return array('type' => 'success');
    }

    public function uploadAvatar()
    {
        $request = request();
        $validator = Validator::make($request->all(), [
            'file' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return sendCustomResponse($validator->messages()->first(), 'error', 500);
        }
        $namefile = "avatar_" . $request->user()->last_id;

        $fileName = $namefile . "" . time() . '.' . request()->file->getClientOriginalExtension();
        $folder_type = 'tmpfiles/avatars';
        $request->file->storeAs($folder_type, $fileName);
        $web_url = "/" . Env("APP_FILES_DIR") . "/" . $folder_type . "/" . $fileName;
        $user = $request->user();
        $user->avatar = $web_url;
        $user->save();
        return array('files' => array('file' => $web_url));
    }
}