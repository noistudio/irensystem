<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudBlogCategorysAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"blog_categorys_access","fields":{"category_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Select","options":{"table":"blog_categorys","pk":"last_id","title":"title"}},"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"ID \u043f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044f","type":"Select","options":{"table":"proj_users","pk":"last_id","title":"username"}},"onlyread":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041c\u043e\u0436\u0435\u0442 \u0447\u0438\u0442\u0430\u0442\u044c","type":"Checkbox","options":[]},"write":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041c\u043e\u0436\u0435\u0442 \u043f\u0438\u0441\u0430\u0442\u044c","type":"Checkbox","options":[]}},"title":"\u0411\u043b\u043e\u0433.\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438.\u0414\u043e\u0441\u0442\u0443\u043f\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('blog_categorys_access');
    }
}
