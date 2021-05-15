<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\PortfolioCategory;

class Portfolio extends Controller
{

    public function categorys()
    {
        $categorys = PortfolioCategory::query()->where("enable", 1)->get();

        return $categorys;
    }

    public function all()
    {
        $user = request()->user();
        $portfolio = \App\Portfolio::query()->with("user", "category")->where("user_id", $user->last_id)->where(
            "enable",
            1
        )->get();

        return $portfolio;
    }

    public function delete($id)
    {

        $user = request()->user();
        \App\Portfolio::query()->where("user_id", $user->last_id)->where("last_id", $id)->delete();

        return array('type' => 'success');
    }

    public function add()
    {
        $user = request()->user();
        if (!(isset($user->isdeveloper) and $user->isdeveloper == 1)) {
            return array(
                'type' => 'error',
                'err_code' => 'not_have_right',
                'message' => 'У вас нет прав,для работы с портфолио',
            );

        }
        $form = request()->post();
        $category = null;
        if (!(isset($form['date_start']) and is_string($form['date_start']) and (bool)strtotime($form['date_start']))) {
            return array(
                'type' => 'error',
                'err_code' => 'date_start_missing',
                'message' => 'Дата начала не заполнена!',
            );
        }

        if (!(isset($form['date_end']) and is_string($form['date_end']) and (bool)strtotime($form['date_end']))) {
            return array(
                'type' => 'error',
                'err_code' => 'date_end_missing',
                'message' => 'Дата завершения не заполнена!',
            );
        }

        if (isset($form['category']) and is_numeric($form['category'])) {
            $category = PortfolioCategory::query()->where("last_id", $form['category'])->first();
        }
        if (!$category) {
            return array('type' => 'error', 'err_code' => 'category_missing', 'message' => 'Категория не найдена');
        }


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
            return array(
                'type' => 'error',
                'err_code' => 'description_missing',
                'message' => 'Введите описание работы',
            );
        }

        if ($header == null) {
            return array('type' => 'error', 'err_code' => 'title_missing', 'message' => 'Вы не указали Заголовок');
        }
        if ($image == null) {
            return array('type' => 'error', 'err_code' => 'image_missing', 'message' => 'Вы не загрузили изображение');
        }

        if (isset($form['last_id']) and is_numeric($form['last_id'])) {
            $portfolio = \App\Portfolio::query()->where("user_id", $user->last_id)->where(
                "last_id",
                $form['last_id']
            )->first();
            if (!$portfolio) {
                return array('type' => 'error', 'err_code' => 'work_not_found', 'message' => 'Документ не найден!');
            }
        } else {
            $portfolio = new \App\Portfolio();
        }

        $portfolio->category_id = $category->last_id;
        $portfolio->user_id = $user->last_id;
        $portfolio->image = $image;
        $portfolio->date_start = $form['date_start'];
        $portfolio->description = $description;
        $portfolio->date_end = $form['date_end'];
        $portfolio->json = $form['json'];
        $portfolio->name = strip_tags($header);
        $portfolio->enable = 1;
        $portfolio->save();

        return array('type' => 'success', 'last_id' => $portfolio->last_id, 'message' => 'Работа успешно сохранена!');
    }

}