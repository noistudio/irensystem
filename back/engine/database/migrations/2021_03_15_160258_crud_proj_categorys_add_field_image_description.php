<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjCategorysAddFieldImageDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"image":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435","type":"Elfinder","options":{"isimage":true,"type":"none","width":null,"height":null}},"description":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0443\u0441\u043b\u0443\u0433\u0438","type":"Content","options":[]}}';

        $table_data = json_decode($json_data, true);
            
        $newtable  = \db\JsonQuery::get('proj_categorys', "tables", "name");
        
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
        
         $table = \content\models\TableConfig::get('proj_categorys');
         $json_data = '{"image":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435","type":"Elfinder","options":{"isimage":true,"type":"none","width":null,"height":null}},"description":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435 \u0443\u0441\u043b\u0443\u0433\u0438","type":"Content","options":[]}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
        if (is_array($table) and isset($name_field) and is_string($name_field) > 0 and isset($table["fields"][$name_field])) {
            \content\models\TableConfig::deleteField($table, $name_field);
        }
        }
  
    }
}
