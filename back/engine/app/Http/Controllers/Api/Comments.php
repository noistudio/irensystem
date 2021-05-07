<?php


namespace App\Http\Controllers\Api;


use App\Comment;
use App\Http\Controllers\Controller;
use App\Notify;
use App\Project;
use App\Subcomment;
use Carbon\Carbon;

class Comments extends Controller
{


    public function sendSubComment($project_id, $comment_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $project = null;

        $project = Project::query()->where("last_id", $project_id)->where(function ($query) use ($user_id) {
            $query->orWhere("client_id", $user_id);
            $query->orWhere("developer_id", $user_id);
            $query->orHas("spectator");
        })->first();

        if (is_null($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }
        $comment = Comment::query()->where(function ($query) use ($project, $me, $comment_id) {
            $query->where("project_id", $project->last_id);
            $query->where("last_id", $comment_id);

        })->first();
        if (!$comment) {
            return array('type' => 'error', 'message' => 'Комментарий не найден!');
        }

        $form = request()->post();

        if (!(isset($form['comment']) and is_string($form['comment']) and strlen($form['comment']) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не указали комментарий');
        }

        $new_comment = new Subcomment();
        $new_comment->user_id = $me->last_id;
        $new_comment->comment = $form['comment'];
        $new_comment->project_id = $project_id;
        $new_comment->comment_id = $comment->last_id;
        $new_comment->save();

        Notify::createSendSubComment($new_comment,$comment,$project);
        $comments = Subcomment::query()->with("user")->where("comment_id", $comment_id)->get();

        return array('type' => 'success', "comments" => $comments);
    }

    public function add($project_id)
    {
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $project = null;

        $project = Project::query()->where("last_id", $project_id)->where(function ($query) use ($user_id) {
            $query->orWhere("client_id", $user_id);
            $query->orWhere("developer_id", $user_id);
            $query->orHas("spectator");
        })->first();

        if (is_null($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }
        if (!(isset($newComment['comment']) and is_string($newComment['comment']) and strlen($newComment['comment']) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не заполнили комментарий!');
        }

        $comment = new Comment();
        $comment->user_id = $user_id;
        $comment->comment = $newComment['comment'];
        $comment->project_id = $project->last_id;
        $comment->save();
        $comment->load("user");

        Notify::createSendComment($comment, $project);


        return array('type' => 'success', 'comment' => $comment);


    }
}