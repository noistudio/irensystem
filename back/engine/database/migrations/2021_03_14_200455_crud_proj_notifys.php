<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjNotifys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"proj_notifys","fields":{"who_send_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"User_id \u043a\u0442\u043e \u043e\u0442\u043f\u0440\u0430\u0432\u0438\u043b","type":"Numberint","options":[]},"user_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044c \u0434\u043b\u044f \u043a\u043e\u0442\u043e\u0440\u043e\u0433\u043e \u0443\u0432\u0435\u0434\u043e\u043c\u043b\u0435\u043d\u0438\u0435","type":"Numberint","options":[]},"isread":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u0440\u043e\u0447\u0438\u0442\u0430\u043d\u043e?","type":"Checkbox","options":[]},"json":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u043f\u0430\u0440\u0430\u043c\u0435\u0442\u0440\u044b \u0443\u0432\u0435\u0434\u043e\u043c\u043b\u0435\u043d\u0438\u044f \u0432 json","type":"Text","options":[]}},"title":"\u0423\u0432\u0435\u0434\u043e\u043c\u043b\u0435\u043d\u0438\u0435","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = $table_data["name"];
        $newtable->fields = json_encode($table_data["fields"]);
        $newtable->title = $table_data["title"];
        $newtable->count = $table_data["count"];
        $newtable->sort_field = $table_data["sort_field"];
        $newtable->sort_type = $table_data["sort_type"];
        \content\models\TableConfig::createTable($newtable);

        $newtable->save();
  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \content\models\TableConfig::delete('proj_notifys');
    }
}
