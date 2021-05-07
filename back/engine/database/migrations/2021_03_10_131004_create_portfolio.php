<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"' . config("database.table_prefix") . 'portfolio","fields":{"description":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"description data","type":"Text","options":[]},"image":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"image","type":"Elfinder","options":{"isimage":true,"type":"none","width":null,"height":null}},"date_end":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"Date end","type":"Fdate","options":[]},"date_start":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"Date start","type":"Fdate","options":[]},"name":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0418\u043c\u044f \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438","type":"Stroka","options":[]},"category_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Select","options":{"table":"' . config("database.table_prefix") . 'portfolio_categorys","pk":"last_id","title":"name"}},"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044c","type":"Select","options":{"table":"' . config("database.table_prefix") . 'users","pk":"last_id","title":"username"}},"json":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"JSON data","type":"Text","options":[]}},"title":"\u041f\u043e\u0440\u0442\u0444\u043e\u043b\u0438\u043e","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "portfolio";
        $newtable->fields = json_encode($table_data['fields']);
        $newtable->title = "Портфолио";
        $newtable->count = $table_data['count'];
        $newtable->sort_field = $table_data['sort_field'];
        $newtable->sort_type = $table_data['sort_type'];
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
        TableConfig::delete(config("database.table_prefix") . "portfolio");
    }
}
