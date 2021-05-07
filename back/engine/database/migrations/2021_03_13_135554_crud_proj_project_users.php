<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjProjectUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"proj_project_users","fields":{"project_id":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"Project Id","type":"Numberint","options":[]},"user_id":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"User Id","type":"Numberint","options":[]},"isapprove":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u043b \u0443\u0447\u0430\u0441\u0442\u0438\u0435?","type":"Checkbox","options":[]}},"title":"\u0423\u0447\u0430\u0441\u0442\u043d\u0438\u043a\u0438 \u043f\u0440\u043e\u0435\u043a\u0442\u0430","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('proj_project_users');
    }
}
