<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

class Notify extends Controller
{
    public function all()
    {
        $user = request()->user();
        $form = request()->post();

        if (isset($form['show_only_unread']) and $form['show_only_unread'] == true) {
            return \App\Notify::query()->with("who")->where("user_id", $user->last_id)->where("isread", 0)->orderByDesc(
                "created_at"
            )->get();
        } else {
            return \App\Notify::query()->with("who")->where("user_id", $user->last_id)->orderByDesc("created_at")->get(
            );
        }


    }

    public function readall()
    {
        \App\Notify::query()->where("user_id", request()->user()->last_id)->update(array('isread' => 1));

        return array('type' => 'success');
    }

    public function remove($id)
    {
        $notify = \App\Notify::query()->where("user_id", request()->user()->last_id)->where("last_id", $id)->first();
        if (!isset($notify)) {
            return array();
        }


        $notify->delete();

        return array('type' => 'success');
    }

    public function setread($id)
    {
        $notify = \App\Notify::query()->where("user_id", request()->user()->last_id)->where("last_id", $id)->first();
        if (!isset($notify)) {
            return array();
        }

        $notify->isread = 1;
        $notify->save();

        return array('type' => 'success');
    }

    public function count()
    {
        $user = request()->user();
        $result = array();
        $result['count'] = \App\Notify::query()->with("who")->where("user_id", $user->last_id)->where(
            "isread",
            0
        )->count();

        return $result;
    }

}