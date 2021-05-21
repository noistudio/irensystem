<?php


namespace managers\backend\controllers;

use App\Portfolio;
use App\Post;
use App\Project;
use content\fields\Oneeditorjs;
use content\models\AbstractField;

class WorkBackend extends \managers\backend\AdminController
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
        $work = Portfolio::query()->where("last_id", $id)->first();

        if ($work) {
            $post = request();
            if (isset($post['json'])) {


                $oneeditor = new Oneeditorjs($post['json'], "json");
                $result = $oneeditor->set();

                $array_data = json_decode($result, true);
                if (is_array($array_data)) {
                    $form['json'] = $array_data;
                    $header = null;
                    $image = null;
                    $description = "";
                    if (isset($form['json']['blocks']) and is_array($form['json']['blocks']) and count(
                            $form['json']['blocks']
                        ) > 0) {
                        foreach ($form['json']['blocks'] as $block) {
                            if (isset($block['type']) and $block['type'] == "delimiter") {
                                break;
                            }

                            if (isset($block['type']) and $block['type'] == "header" and isset($block['data']) and isset($block['data']['text'])
                                and strlen($block['data']['text']) > 0 and is_null($header)
                            ) {
                                $header = $block['data']['text'];

                            }

                            if (isset($block['type']) and $block['type'] == "image" and isset($block['data']['file']) and isset($block['data']['file']['url'])
                                and is_null($image) and strlen($block['data']['file']['url']) > 0 and filter_var(
                                    $block['data']['file']['url'],
                                    FILTER_VALIDATE_URL
                                )
                            ) {
                                $image = $block['data']['file']['url'];

                            }
                            if (isset($block['type']) and $block['type'] == "paragraph" and isset($block['data']['text']) and mb_strlen(
                                    $block['data']['text']
                                )) {
                                $description = $description."<br>".$block['data']['text'];
                            }


                        }
                    }


                    if (!(isset($description) and mb_strlen($description) > 0)) {
                        return back();
                    }

                    if ($header == null) {
                        return back();
                    }
                    if ($image == null) {
                        return back();
                    }


                    $work->image = $image;
                    $work->description = $description;
                    $work->name = strip_tags($header);
                    $work->json = $array_data;
                    $work->save();
                }

            }
        }


        return back();
    }

}