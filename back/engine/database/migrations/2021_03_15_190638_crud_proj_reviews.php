<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"proj_reviews","fields":{"invoice_id":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"Invoice ID","type":"Numberint","options":[]},"who_send":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"User Id \u043a\u0442\u043e \u043e\u0441\u0442\u0430\u0432\u0438\u043b","type":"Numberint","options":[]},"user_id":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"User Id","type":"Numberint","options":[]},"review":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"\u041e\u0442\u0437\u044b\u0432","type":"Text","options":[]},"rating":{"unique":0,"showsearch":1,"order":1,"required":1,"showinlist":1,"title":"\u0420\u0435\u0439\u0442\u0438\u043d\u0433","type":"Numberint","options":[]}},"title":"\u041e\u0442\u0437\u044b\u0432\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('proj_reviews');
    }
}
