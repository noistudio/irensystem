<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectUser;
use App\User;


class Users extends Controller
{

    public function delete($project_id, $user_id)
    {
        $me = request()->user();
        $form = request()->post();
        $project = Project::query()->where("last_id", $project_id)->where("developer_id", "!=", 0)->where(function ($query) use ($me) {
            $query->orWhere("developer_id", $me->last_id);
            $query->orWhere("client_id", $me->last_id);
        })->first();
        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }

        $project_user = ProjectUser::query()->where("project_id", $project->last_id)->where("user_id", $user_id)->first();
        if (!$project_user) {
            return array('type' => 'error', 'message' => 'Пользователь не найден');
        }
        $project_user->delete();
        return array('type' => 'success', 'users' => $project->getUsers());
    }

    public function add($project_id)
    {

        $me = request()->user();
        $form = request()->post();
        $project = Project::query()->where("last_id", $project_id)->where("developer_id", "!=", 0)->where(function ($query) use ($me) {
            $query->orWhere("developer_id", $me->last_id);
            $query->orWhere("client_id", $me->last_id);
        })->first();
        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }

        $new_user = null;
        if (isset($form['username']) and is_string($form['username']) and strlen($form['username']) > 0) {
            $new_user = User::query()->where("username", $form['username'])->first();
        }

        if (!$new_user) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }

        if ($new_user->last_id == $project->client_id) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }
        if ($new_user->last_id == $project->developer_id) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }
        $project_user = ProjectUser::query()->where("project_id", $project->last_id)->where("user_id", $new_user->last_id)->first();

        if ($project_user and $project_user->isapprove == 0) {
            return array('type' => 'error', 'message' => 'Пользователь еще не принял приглашение!');
        }

        if ($project_user) {
            return array('type' => 'error', 'message' => 'Пользователь уже в участниках!');
        }

        $project_user = new ProjectUser();
        $project_user->project_id = $project->last_id;
        $project_user->user_id = $new_user->last_id;
        $project_user->isapprove = 0;
        $project_user->save();
        \App\Notify::createInviteUser($project, $new_user);

        return array('type' => 'success', 'users' => $project->getUsers());
    }

}