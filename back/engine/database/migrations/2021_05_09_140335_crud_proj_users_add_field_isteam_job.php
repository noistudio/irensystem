<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjUsersAddFieldIsteamJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"isteam":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u043e\u043a\u0430\u0437\u044b\u0432\u0430\u0442\u044c \u043d\u0430 \u0433\u043b\u0430\u0432\u043d\u043e\u0439 \u0432 \u0440\u0430\u0437\u0434\u0435\u043b\u0435 \u043a\u043e\u043c\u0430\u043d\u0434\u0430","type":"Checkbox","options":[]},"job":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0414\u043e\u043b\u0436\u043d\u043e\u0441\u0442\u044c","type":"Stroka","options":[]}}';

        $table_data = json_decode($json_data, true);
            
        $newtable  = \db\JsonQuery::get('proj_users', "tables", "name");
        
        $fields=json_decode($newtable->fields,true);
        foreach($table_data as $name_field=>$new_field){
        $fields[$name_field]=$new_field;
        \content\models\TableConfig::addField($newtable, $name_field, $new_field); 
        } 
        $newtable->fields = json_encode($fields);
        

        $newtable->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
         $table = \content\models\TableConfig::get('proj_users');
         $json_data = '{"isteam":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041f\u043e\u043a\u0430\u0437\u044b\u0432\u0430\u0442\u044c \u043d\u0430 \u0433\u043b\u0430\u0432\u043d\u043e\u0439 \u0432 \u0440\u0430\u0437\u0434\u0435\u043b\u0435 \u043a\u043e\u043c\u0430\u043d\u0434\u0430","type":"Checkbox","options":[]},"job":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0414\u043e\u043b\u0436\u043d\u043e\u0441\u0442\u044c","type":"Stroka","options":[]}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
        if (is_array($table) and isset($name_field) and is_string($name_field) > 0 and isset($table["fields"][$name_field])) {
            \content\models\TableConfig::deleteField($table, $name_field);
        }
        }
  
    }
}
