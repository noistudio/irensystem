<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudAbout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"about","fields":{"title":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0413\u043b\u0430\u0432\u043d\u0430\u044f \u0441\u0442\u0440\u0430\u043d\u0438\u0446\u0430","type":"Stroka","options":[]},"header":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0425\u0435\u0434\u0435\u0440","type":"Text","options":[]},"content":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041a\u043e\u043d\u0442\u0435\u043d\u0442","type":"Text","options":[]}},"title":"\u041e \u0441\u0430\u0439\u0442\u0435","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('about');
    }
}
