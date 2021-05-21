<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesToUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'files_to_upload',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string("code", 20)->unique();
                $table->string("file", 300);
                $table->string("yandex_url", 300)->nullable(true);
                $table->boolean("is_start_upload")->default(false);
                $table->boolean("is_upload")->default(true);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_to_upload');
    }
}
