<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateProjectCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"proj_project_comments","fields":{"comment":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u043e\u043c\u043c\u0435\u043d\u0442\u0430\u0440\u0438\u0439","type":"Content","options":[]},"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044c","type":"Select","options":{"table":"' . config("database.table_prefix") . 'users","pk":"last_id","title":"username"}},"project_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041f\u0440\u043e\u0435\u043a\u0442","type":"Select","options":{"table":"' . config("database.table_prefix") . 'projects","pk":"last_id","title":"name_project"}}},"title":"\u041f\u0440\u043e\u0435\u043a\u0442\u044b:\u041a\u043e\u043c\u043c\u0435\u043d\u0442\u0430\u0440\u0438\u0438","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "project_comments";
        $newtable->fields = json_encode($table_data['fields']);
        $newtable->title = $table_data['title'];
        $newtable->count = $table_data['count'];
        $newtable->sort_field = $table_data['sort_field'];
        $newtable->sort_type = $table_data['sort_type'];
        TableConfig::createTable($newtable);

        $newtable->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TableConfig::delete(config("database.table_prefix") . "project_comments");
    }
}
