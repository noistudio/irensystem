<?php


namespace managers\backend\controllers;

use App\Project;
use content\fields\Oneeditorjs;

class ProjectBackend extends \managers\backend\AdminController
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

    public function save($id)
    {
        $project = Project::query()->where("last_id", $id)->first();

        if ($project) {
            $post = request();
            if (isset($post['json'])) {


                $oneeditor = new Oneeditorjs($post['json'], "json");
                $result = $oneeditor->set();

                $array_data = json_decode($result, true);
                $project->json = $array_data;
                $project->save();

            }
        }


        return back();
    }

}