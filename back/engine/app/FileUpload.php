<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Leonied7\Yandex\Disk;

class FileUpload extends Model
{
    public $timestamps = true;
    public $table = "files_to_upload";
    public $primaryKey = "id";

    static function moveToYandexDisk()
    {
        $file_upload = FileUpload::query()->where("is_upload", 0)->where("is_start_upload", 0)->first();
        if ($file_upload) {

            try {
                $yandexDisk = new Disk(env("YANDEX_API_WEB_DAV_TOKEN"));
                $file = Storage::disk('yandexfiles')->get($file_upload->file);
                $path_file = public_path("engine".Storage::url("yandexfiles/".$file_upload->file));


                if (file_exists($path_file)) {

                    $file_upload->is_start_upload = 1;
                    $file_upload->save();
                    $original_name = $file_upload->file;


                    $file = $yandexDisk->file(env("YANDEX_FOLDER").$original_name);
                    $tmp_result = $file->upload(
                        new Disk\Stream\File($path_file),
                        Disk\Stream\File::MODE_READ
                    );

                    $file->startPublish(); // bool
                    $url = Disk\Collection\ResultList::getInstance()->getLast()->getResult();


                    $file_upload->is_upload = 1;
                    
                    $file_upload->yandex_url = $url;

                    $file_upload->save();
                    $file = Storage::disk('yandexfiles')->delete($file_upload->file);

                }

            } catch (\Exception $e) {
                $file_upload->is_start_upload = 0;
                $file_upload->is_upload = 0;
                $file_upload->save();
            }
        }

        return true;

    }

}