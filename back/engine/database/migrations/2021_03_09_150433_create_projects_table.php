<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"proj_projects","fields":{"name_project":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u043f\u0440\u043e\u0435\u043a\u0442\u0430","type":"Stroka","options":[]},"client_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u043b\u0438\u0435\u043d\u0442","type":"Select","options":{"table":"' . config("database.table_prefix") . 'users","pk":"last_id","title":"username"}},"developer_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0418\u0441\u043f\u043e\u043b\u043d\u0438\u0442\u0435\u043b\u044c","type":"Select","options":{"table":"' . config("database.table_prefix") . 'users","pk":"last_id","title":"username"}},"category_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Select","options":{"table":"' . config("database.table_prefix") . 'categorys","pk":"last_id","title":"name"}},"start_time":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041d\u0430\u0447\u0430\u043b\u043e \u043f\u0440\u043e\u0435\u043a\u0442\u0430","type":"Ftime","options":[]},"end_time":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041e\u043a\u043e\u043d\u0447\u0430\u043d\u0438\u0435 \u043f\u0440\u043e\u0435\u043a\u0442\u0430","type":"Ftime","options":[]},"isclose":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0417\u0430\u043a\u0440\u044b\u0442 \u043f\u0440\u043e\u0435\u043a\u0442?","type":"Checkbox","options":[]},"url_task":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"Url \u0437\u0430\u0434\u0430\u043d\u0438\u044f","type":"Stroka","options":[]}},"title":"\u041f\u0440\u043e\u0435\u043a\u0442\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "projects";
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
        TableConfig::delete(config("database.table_prefix") . "projects");
    }
}
