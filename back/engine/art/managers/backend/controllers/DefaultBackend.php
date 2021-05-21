<?php

namespace managers\backend\controllers;

use App\About;
use App\BlogCategory;
use App\BlogCategoryAccess;
use App\Category;
use App\PortfolioCategory;
use App\Post;
use App\PostComment;
use App\PostCommentComment;
use App\Project;
use App\Status;
use App\User;
use App\UserCategory;

class DefaultBackend extends \managers\backend\AdminController
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

    public function actionIndex()
    {


        $object = \db\SqlDocument::object();
        $data = array();
        $data['services'] = Category::query()->get();
        // $data['services'] = array();
        $data['user_services'] = UserCategory::query()->get();

        $data['about'] = About::query()->get();

        $data['count_status_search'] = Status::query()->where("issearch", 1)->count();
        $data['count_status_price'] = Status::query()->where("isprice", 1)->count();
        $data['count_status_work'] = Status::query()->where("iswork", 1)->count();
        $data['count_status_work'] = Status::query()->where("isfinish", 1)->count();
        $data['blog_category_count'] = BlogCategory::query()->where("ismain", 1)->count();
        $data['blog_access'] = BlogCategoryAccess::query()->count();
        $data['portfolio_category'] = PortfolioCategory::query()->count();

        $data['count_developers'] = User::query()->where("enable", 1)->where("isdeveloper", 1)->count();

        $data['count_team'] = User::query()->where("enable", 1)->where("isteam", 1)->where("isdeveloper", 1)->count();
        $data['developers'] = User::query()->where("enable", 1)->where("isdeveloper", 1)->get();
        $data['developers_by_category'] = UserCategory::query()->with(["category", "user"])->get();
        $data['blog_last_comments'] = PostComment::query()->with("user")->orderByDesc("last_id")->limit(20)->get();
        $data['blog_last_subcomments'] = PostCommentComment::query()->with("user")->orderByDesc("last_id")->limit(
            20
        )->get();
        $data['blog_posts'] = Post::query()->where("enable", 1)->orderByDesc("last_id")->limit(20)->get();
        $data['projects'] = Project::query()->with('status_row')->orderByDesc("last_id")->limit(20)->get();


        return $this->render("index", $data);
    }

    public function actionLogout()
    {
        \admins\models\AdminAuth::logout();

        \core\ManagerConf::redirect("/");
    }

    public function manifestJson()
    {
        $output = \managers\backend\models\AdminPwa::generate(false);

        return response()->json($output);
    }

}
