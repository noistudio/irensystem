<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudBlogPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"blog_posts","fields":{"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"User ID","type":"Numberint","options":[]},"category":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f","type":"Select","options":{"table":"blog_categorys","pk":"last_id","title":"title"}},"disable_comments":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041a\u043e\u043c\u043c\u0435\u043d\u0442\u0430\u0440\u0438\u0438 \u043e\u0442\u043a\u043b\u044e\u0447\u0435\u043d\u044b","type":"Checkbox","options":[]},"short":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0410\u043d\u043e\u043d\u0441","type":"Text","options":[]},"content":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0422\u0435\u043a\u0441\u0442","type":"Text","options":[]}},"title":"\u0411\u043b\u043e\u0433\u0438.\u041f\u043e\u0441\u0442\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('blog_posts');
    }
}
