<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"proj_users","fields":{"name":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":1,"title":"\u0418\u043c\u044f","type":"Stroka","options":[]},"account":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":1,"title":"account(rekv)","type":"Text","options":[]},"avatar":{"unique":0,"showsearch":0,"order":1, "required":0,"showinlist":0,"title":"avatar","type":"Elfinder",
  "options":{"isimage":true,"type":"none","width":null,
 "height":null}},"username":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"login \u0432 TG","type":"Stroka","options":[]},"telegram_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"Telegram ID","type":"Numberint","options":[]},"isdeveloper":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0420\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u0447\u0438\u043a?","type":"Checkbox","options":[]},"api_token":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":1,"title":"API Token","type":"Stroka","options":[]}},"title":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u0438","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';
        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "users";
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
        TableConfig::delete(config("database.table_prefix") . "users");

    }
}
