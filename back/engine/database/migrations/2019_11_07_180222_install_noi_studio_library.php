<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstallNoiStudioLibrary extends Migration
{

    public $files = array(
        '_object.config.json' => '{"last_id":2,"schema":{"id":"integer","params":"string"},"relations":[]}',
        '_object.data.json' => '[{"id":1,"params":""}]',
        'blocks.config.json' => '{"last_id":1,"schema":{"id":"integer","title":"string","html_languages":"string","html":"string","type":"string","type_arr":"string","params":"string","status":"integer"},"relations":[]}',
        'blocks.data.json' => '[]',
        'builders.config.json' => '{"last_id":0,"schema":{"id":"integer","name":"string","html":"string","json":"string"},"relations":[]}',
        'builders.data.json' => '[]',
        'collections.config.json' => '{"last_id":0,"schema":{"id":"integer","sort_field":"string","sort_type":"string","name":"string","title":"string","fields":"string","connections":"string","count":"integer"},"relations":[]}',
        'collections.data.json' => '[]',
        'forms.config.json' => '{"last_id":1,"schema":{"id":"integer","type":"string","send_on_email_admin":"string","notify":"string","title":"string","email":"string","fields":"string"},"relations":[]}',
        'forms.data.json' => '[]',
        'logs.config.json' => '{"last_id":0,"schema":{"id":"integer","status":"string","channel":"string","level":"string","params":"string"},"relations":[]}',
        'logs.data.json' => '[]',
        'menus.config.json' => '{"last_id":1,"schema":{"id":"integer","title":"string","links":"string","status":"integer"},"relations":[]}',
        'menus.data.json' => '[]',
        'routes.config.json' => '{"last_id":0,"schema":{"id":"integer","old_url":"string","new_url":"string","title":"string","meta_keywords":"string","langs":"string","meta_description":"string"},"relations":[]}',
        'routes.data.json' => '[]',
        'tables.config.json' => '{"last_id":1,"schema":{"id":"integer","sort_field":"string","sort_type":"string","name":"string","title":"string","fields":"string","count":"integer"},"relations":[]}',
        'tables.data.json' => '[]',


    );

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function createFile($name, $content)
    {
        $fp = fopen(LAZER_DATA_PATH.$name, 'w');
        fwrite($fp, $content);
        fclose($fp);
        chmod(LAZER_DATA_PATH.$name, 0777);
        // file_put_contents(LAZER_DATA_PATH.$name,$content);
    }

    public function deleteFile($name)
    {
        unlink(LAZER_DATA_PATH.$name);

    }

    public function up()
    {


        if (!\core\ManagerConf::isOnlyMongodb()) {


            Schema::create(
                'elfinder_files',
                function (Blueprint $table) {
                    $table->string('file', 200);
                    $table->increments('id_file');
                    $table->string("min_image", 200);
                    $table->string("type", 255);
                }
            );
            Schema::create(
                'multiselect',
                function (Blueprint $table) {

                    $table->string("data_table", 200);
                    $table->string("from_table", 200);
                    $table->string("value", 200);
                    $table->increments('last_id');
                    $table->integer("row_id");
                }
            );
        }

        // $files = scandir(LAZER_DATA_PATH);

        if (count($this->files)) {
            foreach ($this->files as $file => $content) {
                $this->createFile($file, $content);
            }
        }
//        if (count($files)) {
//            foreach ($files as $file) {
//                if ($file != "_object.config.json" and $file != "_object.data.json") {
//                    $path_to_file = LAZER_DATA_PATH . "/" . $file;
//
//                    $is_config = str_replace("config.json", "", $file);
//                    $is_data = str_replace("data.json", "", $file);
//                    if ($file != $is_config or $file != $is_data) {
//                        $content_file = file_get_contents($path_to_file);
//                        $json = json_decode($content_file, true);
//                        if ($file != $is_config) {
//                            $json['last_id'] = 0;
//                        } else if ($file != $is_data) {
//                            $json = array();
//                        }
//                        file_put_contents($path_to_file, json_encode($json));
//                    }
//                }
//            }
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (!\core\ManagerConf::isOnlyMongodb()) {

            Schema::dropIfExists('elfinder_files');
            Schema::dropIfExists('multiselect');
        }

        if (count($this->files)) {
            foreach ($this->files as $file => $content) {
                $this->deleteFile($file);
            }
        }

//        $files = scandir(LAZER_DATA_PATH);
//        if (count($files)) {
//            foreach ($files as $file) {
//                if ($file != "_object.config.json" and $file != "_object.data.json") {
//                    $path_to_file = LAZER_DATA_PATH . "/" . $file;
//
//                    $is_config = str_replace("config.json", "", $file);
//                    $is_data = str_replace("data.json", "", $file);
//                    if ($file != $is_config or $file != $is_data) {
//                        $content_file = file_get_contents($path_to_file);
//                        $json = json_decode($content_file, true);
//                        if ($file != $is_config) {
//                            $json['last_id'] = 0;
//                        } else if ($file != $is_data) {
//                            $json = array();
//                        }
//                        file_put_contents($path_to_file, json_encode($json));
//                    }
//                }
//            }
//        }
    }

}
