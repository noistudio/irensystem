<?php

use content\models\TableConfig;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"users_categorys","fields":{"category_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Select","options":{"table":"' . config("database.table_prefix") . 'categorys","pk":"last_id","title":"name"}},"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044c","type":"Select","options":{"table":"' . config("database.table_prefix") . 'users","pk":"last_id","title":"username"}}},"title":"\u041e\u0442\u0432\u0435\u0442\u0441\u0442\u0432\u0435\u043d\u043d\u044b\u0435 \u0437\u0430 \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "users_categorys";
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
        TableConfig::delete(config("database.table_prefix") . "users_categorys");
    }
}
