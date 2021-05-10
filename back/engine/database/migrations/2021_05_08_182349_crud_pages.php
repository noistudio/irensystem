<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"pages","fields":{"name":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u0432 \u043c\u0435\u043d\u044e","type":"Stroka","options":[]},"title":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":0,"title":"\u0417\u0430\u0433\u043e\u043b\u043e\u0432\u043e\u043a","type":"Stroka","options":[]},"content":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":0,"title":"\u041a\u043e\u043d\u0442\u0435\u043d\u0442","type":"Content","options":[]}},"title":"\u0421\u0442\u0440\u0430\u043d\u0438\u0446\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('pages');
    }
}
