<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjProjectsRemoveFieldClientId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    $field='client_id';
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
         $json_data = '{"client_id":{"unique":0,"showsearch":0,"order":1,"required":1,"showinlist":1,"title":"\u041a\u043b\u0438\u0435\u043d\u0442","type":"Select","options":{"table":"proj_users","pk":"last_id","title":"username"},"type_name":"\u0421\u043f\u0438\u0441\u043e\u043a","config":{"table":{"type":"text","title":"\u0418\u043c\u044f \u0442\u0430\u0431\u043b\u0438\u0446\u044b"},"pk":{"type":"text","title":"Primary Key"},"title":{"type":"text","title":"\u041d\u0430\u0437\u0432\u0430\u043d\u0438\u0435 \u043f\u043e\u043b\u044f \u0437\u0430\u0433\u043e\u043b\u043e\u0432\u043a\u0430"}},"obj":{},"name":"client_id"}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
              $fields[$name_field] = $new_field;
            \content\models\TableConfig::addField($newtable, $name_field, $new_field);
            
     
        }
        $newtable->fields = json_encode($fields);
        $newtable->save();
    
    }
}
