<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudBlogPostsCommentsComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
   $json_data = '{"name":"blog_posts_comments_comments","fields":{"user_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"User_ID","type":"Numberint","options":[]},"post_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"Post ID","type":"Numberint","options":[]},"comment_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"Comment ID","type":"Numberint","options":[]},"comment":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u043e\u043c\u043c\u0435\u043d\u0442\u0430\u0440\u0438\u0439","type":"Text","options":[]}},"title":"\u041f\u043e\u0441\u0442\u044b.\u041f\u043e\u0434\u043a\u043e\u043c\u043c\u0435\u043d\u0442\u044b","count":20,"sort_field":"arrow_sort","sort_type":"ASC"}';

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
        \content\models\TableConfig::delete('blog_posts_comments_comments');
    }
}
