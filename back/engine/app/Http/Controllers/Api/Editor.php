<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \Leonied7\Yandex\Disk;

class Editor extends Controller
{


    public function fetchUrl()
    {
        $url = request()->get("url");
        if (!(isset($url) and is_string($url) and filter_var($url, FILTER_VALIDATE_URL))) {
            return array('error' => 1, 'message' => 'Ошибка!');
        }

        $meta = get_meta_tags($url);
        return array('success' => 1, 'meta' => $meta);

    }

    public function uploadFile()
    {
        $yandexDisk = new Disk(env("YANDEX_API_WEB_DAV_TOKEN"));
        $request = request();

        $file_original = $request->file('file');
        if (!$file_original) {

            return array('success' => 1, 'message' => 'Файл не был передан!');
        }

        // return $request->file('file')->path();

        $original_name = $file_original->getClientOriginalName();

        $parse_path = pathinfo($original_name);
        $parse_path['filename'] = Str::slug($parse_path['filename']);
        $parse_path['filename'] = $parse_path['filename'] . "_" . time();

        $original_name = $parse_path['filename'] . "." . $parse_path['extension'];

        $path_file = $file_original->path();
        $file = $yandexDisk->file(env("YANDEX_FOLDER") . $original_name);
        $file->upload(new Disk\Stream\File($path_file), Disk\Stream\File::MODE_READ);

        $file->startPublish(); // bool
        $url = Disk\Collection\ResultList::getInstance()->getLast()->getResult();


        return array('success' => 1, 'file' => array('url' => $url, 'name' => $file_original->getClientOriginalName()));
    }

    public function uploadImage()
    {
        $request = request();
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return array('error' => 1, 'message' => $validator->messages()->first());
        }
        $namefile = "avatar_" . $request->user()->last_id;

        $fileName = $namefile . "" . time() . '.' . request()->image->getClientOriginalExtension();
        $folder_type = 'tmpfiles/editor';
        $request->image->storeAs($folder_type, $fileName);
        $web_url = "/" . Env("APP_FILES_DIR") . "/" . $folder_type . "/" . $fileName;;

        return array('success' => 1, 'file' => array('url' => URL::to($web_url)));
    }
}