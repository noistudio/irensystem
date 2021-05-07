<?php

use content\models\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"name":"proj_invoices","fields":{"project_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041f\u0440\u043e\u0435\u043a\u0442","type":"Select","options":{"table":"'.config("database.table_prefix").'projects","pk":"last_id","title":"name_project"}},"sum":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0421\u0443\u043c\u043c\u0430","type":"Numberint","options":[]},"currency":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0412\u0430\u043b\u044e\u0442\u0430","type":"Stroka","options":[]},"client_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u043b\u0438\u0435\u043d\u0442","type":"Select","options":{"table":"'.config("database.table_prefix").'users","pk":"last_id","title":"username"}},"developer_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u0418\u0441\u043f\u043e\u043b\u043d\u0438\u0442\u0435\u043b\u044c","type":"Select","options":{"table":"'.config("database.table_prefix").'users","pk":"last_id","title":"username"}},"title":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0443\u0441\u043b\u0443\u0433\u0438","type":"Stroka","options":[]},"ispay":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0421\u0447\u0435\u0442 \u043e\u043f\u043b\u0430\u0447\u0435\u043d","type":"Checkbox","options":[]},"client_pay":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041a\u043b\u0438\u0435\u043d\u0442 \u043f\u043e\u043c\u0435\u0442\u0438\u043b \u0441\u0447\u0435\u0442 \u043e\u043f\u043b\u0430\u0447\u0435\u043d","type":"Checkbox","options":[]}},"title":"\u0421\u0444\u043e\u0440\u043c\u0438\u0440\u043e\u0432\u0430\u043d\u043d\u044b\u0435 \u0441\u0447\u0435\u0442\u0430","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

        $table_data = json_decode($json_data, true);

        $newtable = \db\JsonQuery::insert("tables");
        $newtable->name = config("database.table_prefix") . "invoices";
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
        TableConfig::delete(config("database.table_prefix") . "invoices");
    }
}
