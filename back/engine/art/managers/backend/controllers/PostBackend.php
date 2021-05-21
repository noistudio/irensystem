<?php


namespace managers\backend\controllers;

use App\Post;
use App\Project;
use content\fields\Oneeditorjs;
use content\models\AbstractField;

class PostBackend extends \managers\backend\AdminController
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
        $blog_post = Post::query()->where("last_id", $id)->first();

        if ($blog_post) {
            $post = request();
            if (isset($post['json'])) {


                $oneeditor = new Oneeditorjs($post['json'], "json");
                $result = $oneeditor->set();

                $array_data = json_decode($result, true);
                if (is_array($array_data)) {
                    $form['json'] = $array_data;
                    $short = array();
                    $short = $form['json'];
                    $short['blocks'] = array();

                    if (isset($form['json']['blocks']) and is_array($form['json']['blocks']) and count(
                            $form['json']['blocks']
                        ) > 0) {
                        foreach ($form['json']['blocks'] as $block) {

                            if (isset($block['type']) and $block['type'] == "delimiter") {

                                break;
                            }
                            $short['blocks'][] = $block;


                        }
                    }


                    $content = $form['json'];

                    $blog_post->short = $short;
                    $blog_post->content = $content;
                    $blog_post->save();
                }

            }
        }


        return back();
    }

}