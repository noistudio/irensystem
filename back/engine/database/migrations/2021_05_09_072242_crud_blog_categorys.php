<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudBlogCategorys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"blog_categorys","fields":{"title":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438","type":"Stroka","options":[]},"image":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435","type":"Elfinder","options":{"isimage":true,"type":"none","width":0,"height":0}},"description":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435","type":"Content","options":[]},"ispublic":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041c\u043e\u0433\u0443\u0442 \u043f\u0438\u0441\u0430\u0442\u044c \u0432\u0441\u0435","type":"Checkbox","options":[]},"ismain":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u043e\u043a\u0430\u0437\u044b\u0432\u0430\u0442\u044c \u043f\u043e\u0441\u0442\u044b \u0438\u0437 \u044d\u0442\u043e\u0439 \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438 \u043d\u0430 \u0433\u043b\u0430\u0432\u043d\u043e\u0439","type":"Checkbox","options":[]},"isprivate":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0421\u043a\u0440\u044b\u0442\u0430\u044f \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Checkbox","options":[]}},"title":"\u0411\u043b\u043e\u0433\u0438.\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('blog_categorys');
    }
}
