<?php


namespace App\Http\Controllers\Api;


use App\FileUpload;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Storage;


class Download extends Controller
{

    public function file($code)
    {

        $fileupload = FileUpload::query()->where("code", $code)->first();
        if ($fileupload) {
            if ($fileupload->is_upload) {
                return redirect($fileupload->yandex_url);
            } else {
                return Storage::disk('yandexfiles')->download($fileupload->file);
            }

        }

        return array('type' => 'error', 'err_code' => 'file_not_found', 'message' => 'Файл не найден!');

    }

}