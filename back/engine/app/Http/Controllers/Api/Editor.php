<?php


namespace App\Http\Controllers\Api;


use App\FileUpload;
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

        $result_meta = array();
        if (isset($meta['description'])) {
            $result_meta['description'] = $meta['description'];
        }
        if (isset($meta['title'])) {
            $result_meta['title'] = $meta['title'];
        }
        if (isset($meta['keywords'])) {
            $result_meta['keywords'] = $meta['keywords'];
        }

        return array('success' => 1, 'meta' => $result_meta);

    }

    public function uploadFile()
    {
        $request = request();

        $file_original = $request->file('file');

        if (!$file_original) {

            return array('success' => 1, 'message' => 'Файл не был передан!');
        }
        $original_name = $file_original->getClientOriginalName();

        $parse_path = pathinfo($original_name);
        $parse_path['filename'] = Str::slug($parse_path['filename']);
        $parse_path['filename'] = $parse_path['filename']."_".time();

        $original_name = $parse_path['filename'].".".$parse_path['extension'];
        $folder_type = "yandexfiles";
        //  $fileName = $namefile."".time().'.'.request()->file->getClientOriginalExtension();
        $request->file('file')->storeAs('', $original_name, 'yandexfiles');

        // Storage::disk('yandexfiles')->put($fileName, file_get_contents($file_original->getPathName()));


        $file_upload = new FileUpload();
        $file_upload->code = Str::random(10);
        $file_upload->file = $original_name;
        $file_upload->is_upload = 0;
        $file_upload->save();

        return array(
            'success' => 1,
            'file' => array(
                'url' => route("download", $file_upload->code),
                'name' => $file_original->getClientOriginalName(),
            ),
        );
    }

    public function uploadFileOld()
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
        $parse_path['filename'] = $parse_path['filename']."_".time();

        $original_name = $parse_path['filename'].".".$parse_path['extension'];

        $path_file = $file_original->path();
        $file = $yandexDisk->file(env("YANDEX_FOLDER").$original_name);
        $file->upload(new Disk\Stream\File($path_file), Disk\Stream\File::MODE_READ);

        $file->startPublish(); // bool
        $url = Disk\Collection\ResultList::getInstance()->getLast()->getResult();


        return array('success' => 1, 'file' => array('url' => $url, 'name' => $file_original->getClientOriginalName()));
    }

    public function uploadImage()
    {
        $request = request();
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );

        if ($validator->fails()) {
            return array('error' => 1, 'message' => $validator->messages()->first());
        }
        $fileName = time().'.'.request()->image->getClientOriginalExtension();
        $folder_type = 'tmpfiles/editor';
        $request->image->storeAs($folder_type, $fileName);
        $web_url = "/".Env("APP_FILES_DIR")."/".$folder_type."/".$fileName;;

        return array('success' => 1, 'file' => array('url' => URL::to($web_url)));
    }
}