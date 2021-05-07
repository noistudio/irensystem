<?php

namespace managers\frontend\controllers;

class SiteController extends \managers\frontend\Controller
{

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    function __construct($is_plugin = false)
    {
        parent::__construct($is_plugin);
    }

    public function actionSaveCity($last_id)
    {
        $result = array();
        $result['status'] = \managers\frontend\models\CityModel::set($last_id);

        return $result;
    }

    public function actionCityCurrent()
    {
        $current = \managers\frontend\models\CityModel::get();

        return $current;
    }

    public function actionIndex()
    {


        return array();
    }

}
