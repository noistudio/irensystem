<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjProjectsRemoveFieldUrlTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    $field='url_task';
    $table = \content\models\TableConfig::get('proj_projects');
    \content\models\TableConfig::deleteField($table, $field);
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    $table = \content\models\TableConfig::get('proj_projects');
      $newtable = \db\JsonQuery::get($table["name"], "tables", "name");

        $fields = json_decode($newtable->fields, true);
         $json_data = '{"url_task":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"Url \u0437\u0430\u0434\u0430\u043d\u0438\u044f","type":"Stroka","options":[],"type_name":"\u0422\u0435\u043a\u0441\u0442\u043e\u0432\u043e\u0435 \u043f\u043e\u043b\u0435","config":[],"obj":{},"name":"url_task"}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
              $fields[$name_field] = $new_field;
            \content\models\TableConfig::addField($newtable, $name_field, $new_field);
            
     
        }
        $newtable->fields = json_encode($fields);
        $newtable->save();
    
    }
}
