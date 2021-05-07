<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Notify;
use App\OfferComment;
use App\Project;
use App\ProjectInvoice;
use App\ProjectOffer;
use App\Status;
use App\UserCategory;

class Freelance extends Controller
{


    public function choose($project_id, $offer_id)
    {

        $me = request()->user();
        $project = Project::query()->where(function ($query) use ($me) {

            $query->orWhere(function ($sub_query) use ($me) {
                $sub_query->where("client_id", $me->last_id);
                $sub_query->where("developer_id", 0);

            });


        })->where("last_id", $project_id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }
        $offer = ProjectOffer::query()->where("project_id", $project->last_id)->where("last_id", $offer_id)->first();


        if (!$offer) {
            return array('type' => 'error', 'message' => 'Разработчик не найден!');
        }

        $project->developer_id = $offer->developer_id;
        $status = Status::query()->where("iswork", 1)->first();
        if (!$status) {
            return array('type' => 'error', 'message' => 'Статус не найден!');
        }
        $project->status = $status->last_id;

        $new_invoice = new ProjectInvoice();
        $new_invoice->project_id = $project->last_id;
        $new_invoice->sum = $offer->price;
        $new_invoice->currency = $offer->currency;
        $new_invoice->client_id = $me->last_id;
        $new_invoice->developer_id = $offer->developer_id;
        $new_invoice->title = $offer->comment;
        $new_invoice->is_approve_client = 1;
        $new_invoice->save();

        $project->save();

        Notify::createChooseOffer($offer, $project);

        $project = Project::query()->with("tasks", "tasks.client", "client", "status_row", "offers", "offers.comments", "offers.comments.user", "offers.developer")->where(function ($query) use ($me, $offer) {

            $query->orWhere(function ($sub_query) use ($me, $offer) {
                $sub_query->where("client_id", $me->last_id);
                $sub_query->where("developer_id", $offer->developer_id);
            });


        })->where("last_id", $project->last_id)->first();


        return array('type' => 'success', 'project' => $project);


    }

    public function sendComment($id, $offer_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $develops_category = array();
        if ($me->isdeveloper == 1) {
            $develops_category = UserCategory::query()->where("user_id", $me->last_id)->get()->pluck('last_id')->toArray();

        }


        $project = Project::query()->where(function ($query) use ($me, $develops_category) {

            $query->orWhere(function ($sub_query) use ($me, $develops_category) {
                $sub_query->orWhere("client_id", $me->last_id);
                if ($me->isdeveloper == 1) {
                    $sub_query->orWhere("developer_id", $me->last_id);
                }
            });
            if (count($develops_category) > 0) {
                $query->orWhere(function ($sub_query2) use ($me, $develops_category) {
                    $sub_query2->where("developer_id", 0);
                    $sub_query2->where("category_id", $develops_category);
                });
            }


        })->where("last_id", $id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }

        $offer = ProjectOffer::query()->where(function ($query) use ($project, $me, $offer_id) {
            $query->where("project_id", $project->last_id);
            $query->where("last_id", $offer_id);
            if ($project->client_id != $me->last_id) {
                $query->where("developer_id", $me->last_id);
            }
        })->first();
        if (!$offer) {
            return array('type' => 'error', 'message' => 'Предложение не найдено!');
        }
        $form = request()->post();

        if (!(isset($form['comment']) and is_string($form['comment']) and strlen($form['comment']) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не указали комментарий');
        }

        $new_comment = new OfferComment();
        $new_comment->user_id = $me->last_id;
        $new_comment->comment = $form['comment'];
        $new_comment->offer_id = $offer_id;
        $new_comment->save();

        $comments = OfferComment::query()->with("user")->where("offer_id", $offer_id)->get();

        Notify::createNewCommentOffer($new_comment, $offer, $project);

        return array('type' => 'success', "comments" => $comments);


    }

    public function send($project_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $develops_category = array();

        if ($me->isdeveloper == 0) {
            return array('type' => 'error', 'message' => 'Вы не разработчик вы не можете отвечать ');

        }
        $develops_category = UserCategory::query()->where("user_id", $me->last_id)->get()->pluck('last_id')->toArray();


        $project = Project::query()->with("client", "status_row")->where(function ($query) use ($me, $develops_category) {

            $query->orWhere(function ($sub_query) use ($me, $develops_category) {


                $sub_query->where("developer_id", $me->last_id);

            });
            if (count($develops_category) > 0) {
                $query->orWhere(function ($sub_query2) use ($me, $develops_category) {
                    $sub_query2->where("developer_id", 0);
                    $sub_query2->where("category_id", $develops_category);
                });
            }


        })->where("last_id", $project_id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }
        $form = request()->post();

        if (!(isset($form['price']) and is_numeric($form['price']) and (int)$form['price'] > 0)) {
            return array('type' => 'error', 'message' => 'Цена должна быть больше 0');
        }
        if (!(isset($form['currency']) and is_string($form['currency']) and strlen($form['currency']) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не выбрали валюту');
        }
        if (!(isset($form['date_end']) and is_string($form['date_end']) and (bool)strtotime($form['date_end']))) {
            return array('type' => 'error', 'message' => 'Вы указали сроки');
        }
        if (!(isset($form['comment']) and is_string($form['comment']) and strlen($form['comment']) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не указали комментарий');
        }

        $project_offer = ProjectOffer::query()->with("developer")->where("project_id", $project->last_id)->where("developer_id", $me->last_id)->first();


        if (!$project_offer) {

            $project_offer = new ProjectOffer();
        }
        $project_offer->project_id = $project->last_id;
        $project_offer->developer_id = $me->last_id;
        $project_offer->price = $form['price'];
        $project_offer->currency = $form['currency'];
        $project_offer->date_end = $form['date_end'];
        $project_offer->comment = $form['comment'];
        $project_offer->save();
        $project_offer->load("developer");

        Notify::createNewOfferNotify($project_offer, $project);

        return array('type' => 'success', 'offer' => $project_offer);


    }


}