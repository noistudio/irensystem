<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjProjectsAddFieldDeveloperIdClientIdStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"developer_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"ID \u0440\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u0447\u0438\u043a\u0430","type":"Numberint","options":[]},"client_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"ID \u043a\u043b\u0438\u0435\u043d\u0442\u0430","type":"Numberint","options":[]},"status":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0421\u0442\u0430\u0442\u0443\u0441","type":"Select","options":{"table":"proj_statuses","pk":"last_id","title":"title"}}}';

        $table_data = json_decode($json_data, true);
            
        $newtable  = \db\JsonQuery::get('proj_projects', "tables", "name");
        
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
        
         $table = \content\models\TableConfig::get('proj_projects');
         $json_data = '{"developer_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"ID \u0440\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u0447\u0438\u043a\u0430","type":"Numberint","options":[]},"client_id":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"ID \u043a\u043b\u0438\u0435\u043d\u0442\u0430","type":"Numberint","options":[]},"status":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0421\u0442\u0430\u0442\u0443\u0441","type":"Select","options":{"table":"proj_statuses","pk":"last_id","title":"title"}}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
        if (is_array($table) and isset($name_field) and is_string($name_field) > 0 and isset($table["fields"][$name_field])) {
            \content\models\TableConfig::deleteField($table, $name_field);
        }
        }
  
    }
}
