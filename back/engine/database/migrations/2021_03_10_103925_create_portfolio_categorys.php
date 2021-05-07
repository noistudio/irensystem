<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioCategorys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"proj_portfolio_categorys","fields":{"name":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0418\u043c\u044f \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438","type":"Stroka","options":[]}},"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438 \u0431\u043b\u043e\u0433\u0430","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "portfolio_categorys";
        $newtable->fields = json_encode($table_data['fields']);
        $newtable->title = "Категории в портфолио";
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
        TableConfig::delete(config("database.table_prefix") . "portfolio_categorys");
    }
}
