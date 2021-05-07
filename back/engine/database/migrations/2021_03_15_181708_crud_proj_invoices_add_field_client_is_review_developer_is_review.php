<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrudProjInvoicesAddFieldClientIsReviewDeveloperIsReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json_data = '{"client_is_review":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041a\u043b\u0438\u0435\u043d\u0442 \u043e\u0441\u0442\u0430\u0432\u0438\u043b \u043e\u0442\u0437\u044b\u0432?","type":"Checkbox","options":[]},"developer_is_review":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0418\u0441\u043f\u043e\u043b\u043d\u0438\u0442\u0435\u043b\u044c \u043e\u0441\u0442\u0430\u0432\u0438\u043b \u043e\u0442\u0437\u044b\u0432?","type":"Checkbox","options":[]}}';

        $table_data = json_decode($json_data, true);
            
        $newtable  = \db\JsonQuery::get('proj_invoices', "tables", "name");
        
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
        
         $table = \content\models\TableConfig::get('proj_invoices');
         $json_data = '{"client_is_review":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u041a\u043b\u0438\u0435\u043d\u0442 \u043e\u0441\u0442\u0430\u0432\u0438\u043b \u043e\u0442\u0437\u044b\u0432?","type":"Checkbox","options":[]},"developer_is_review":{"unique":0,"showsearch":0,"order":1,"required":0,"showinlist":0,"title":"\u0418\u0441\u043f\u043e\u043b\u043d\u0438\u0442\u0435\u043b\u044c \u043e\u0441\u0442\u0430\u0432\u0438\u043b \u043e\u0442\u0437\u044b\u0432?","type":"Checkbox","options":[]}}';
         $table_data = json_decode($json_data, true);
         foreach($table_data as $name_field=>$new_field){
        if (is_array($table) and isset($name_field) and is_string($name_field) > 0 and isset($table["fields"][$name_field])) {
            \content\models\TableConfig::deleteField($table, $name_field);
        }
        }
  
    }
}
