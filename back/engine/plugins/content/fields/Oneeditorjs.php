<?php

namespace content\fields;

use content\models\AbstractField;
use \EditorJS\EditorJS;

class Oneeditorjs extends AbstractField
{

    public function set()
    {
        //  $this->setOtherField("", $this->name . "_full");
        //    $this->setOtherField("", $this->name . "_anonce");
        $value = $this->value;

        //$value = \editjs\models\BlocksModel::parseValue($value);


        $result_config = \editjs\models\BlocksModel::getConfig();
         

        $editor = new EditorJS($value, $result_config['config']);

        // Get sanitized blocks (according to the rules from configuration)

        $blocks = $editor->getBlocks();
        if (isset($blocks) and is_array($blocks) and count($blocks) > 0) {
            $result = array();
            $result['blocks'] = $blocks;


            return json_encode($result);
        }

        return "";
    }

    public function getResult($blocks, $config)
    {

        return \editjs\models\BlocksModel::parseResult($blocks, $config);
    }

    public function get()
    {
        $value = $this->value;

        $value = json_decode($value, true);


        if (!(isset($value) and is_array($value))) {
            $value = array();
        }
        $this->value = $value;


        return $this->render();
    }

    public function setTypeLaravel($table_obj)
    {

        $table_obj->binary($this->name);

    }

    public function getFieldTitle()
    {

        return "Oneeditorjs";
    }

    public function _raw_create_sql()
    {
        $result = '`'.$this->name.'` TEXT NULL';

        return $result;
    }

}
