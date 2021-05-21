<?php


namespace App\Http\Controllers\Api;


use App\BlogCategory;
use App\BlogCategoryAccess;
use App\Category;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Notify;
use App\Post;
use App\PostComment;
use App\PostCommentComment;
use App\Project;
use App\Subcomment;
use EditorJS\EditorJS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Posts extends Controller
{


    function categorys()
    {

        $available_categorys = array();

        $user = request()->user();
        $filter_categorys = BlogCategoryAccess::query()->with("category")->where("user_id", $user->last_id)->whereHas(
            "category",
            function ($query) {
                $query->where("isprivate", 1);

            }
        )->get();

        if (count($filter_categorys)) {
            foreach ($filter_categorys as $cat) {
                if ($cat->write == 1 or $cat->isadmin == 1) {
                    $available_categorys[] = $cat->category_id;
                }
            }
        }


        $categorys = BlogCategory::query()->where(
            function ($query) use ($available_categorys) {

                $query->where(
                    function ($subquery) {
                        $subquery->where("enable", 1);
                        $subquery->where("isprivate", 0);
                    }
                );
                if (count($available_categorys)) {
                    $query->orWhere(
                        function ($subquery) use ($available_categorys) {
                            $subquery->WhereIn("last_id", $available_categorys);
                            $subquery->where("enable", 1);
                            $subquery->where("isprivate", 1);
                        }
                    );

                }
            }
        )->orderBy("sort")->get();

        return $categorys;
    }

    public function delete_comment($comment_id, $post_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $post = null;

        $post = Post::query()->with(["my_access"])->where(
            function ($query) use ($post_id) {
                $query->where("enable", 1);
                $query->where("last_id", $post_id);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();

        if (!($post and isset($post->my_access) and isset($post->my_access->isadmin) and $post->my_access->isadmin == 1)) {
            return array('type' => 'error', 'message' => 'Вы не являетесь админом в данной категории');

        }

        $comment = PostComment::query()->where("last_id", $comment_id)->where("post_id", $post->last_id)->first();
        if (!$comment) {
            return array('type' => 'error', 'message' => 'Комментарий не найден!');
        }
        PostCommentComment::query()->where("comment_id", $comment->last_id)->delete();
        $comment->delete();


        $comments = PostComment::query()->with("comments.user")->where("post_id", $post->last_id)->get();

        return array('type' => 'success', 'comments' => $comments);

    }

    public function delete_sub_comment($subcomment_id, $comment_id, $post_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $post = null;

        $post = Post::query()->with(["my_access"])->where(
            function ($query) use ($post_id) {
                $query->where("enable", 1);
                $query->where("last_id", $post_id);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();

        if (!($post and isset($post->my_access) and isset($post->my_access->isadmin) and $post->my_access->isadmin == 1)) {
            return array('type' => 'error', 'message' => 'Вы не являетесь админом в данной категории');

        }

        $subcomment = PostCommentComment::query()->where("last_id", $subcomment_id)->where(
            "comment_id",
            $comment_id
        )->where("post_id", $post->last_id)->first();
        if (!$subcomment) {
            return array('type' => 'error', 'message' => 'Комментарий не найден!');
        }
        $subcomment->delete();


        $comments = PostComment::query()->with("comments.user")->where("post_id", $post->last_id)->get();

        return array('type' => 'success', 'comments' => $comments);

    }

    public function sendSubComment($post_id, $comment_id)
    {
        $me = request()->user();
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $post = null;

        $post = Post::query()->with(["comments.user", "category_post", "user"])->where(
            function ($query) use ($post_id) {
                $query->where("enable", 1);
                $query->where("last_id", $post_id);
                $query->where("disable_comments", 0);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();
        if ($post) {
            if ($post->category_post->isprivate == 1) {
                $post->category_post->load("my_access");
                if (!(isset($post->category_post->my_access) and ($post->category_post->my_access->onlyread == 1 || $post->category_post->my_access->isadmin == 1))) {
                    return array(
                        'type' => 'error',
                        'error_key' => 'not_access_to_post',
                        'message' => 'Пост не найден!',
                    );
                }
            }
        }

        if (is_null($post)) {
            return array('type' => 'error', 'error_key' => 'post_not_found', 'message' => 'Пост не найден!');
        }
        $comment = PostComment::query()->where(
            function ($query) use ($post, $me, $comment_id) {
                $query->where("post_id", $post->last_id);
                $query->where("last_id", $comment_id);

            }
        )->first();
        if (!$comment) {
            return array('type' => 'error', 'error_key' => 'comment_not_found', 'message' => 'Комментарий не найден!');
        }

        $form = request()->post();

        if (!(isset($form['comment']) and is_string($form['comment']) and strlen($form['comment']) > 0)) {
            return array(
                'type' => 'error',
                'error_key' => 'comment_text_not_filled',
                'message' => 'Вы не указали комментарий',
            );
        }

        $new_comment = new PostCommentComment();
        $new_comment->user_id = $me->last_id;
        $new_comment->comment = strip_tags($form['comment']);
        $new_comment->post_id = $post_id;
        $new_comment->comment_id = $comment->last_id;
        $new_comment->save();

        //   Notify::createSendSubComment($new_comment,$comment,$project);
        $comments = PostCommentComment::query()->with("user")->where("comment_id", $comment_id)->get();

        return array('type' => 'success', "comments" => $comments);
    }

    public function addComment($last_id)
    {
        $user_id = request()->user()->last_id;
        $newComment = request()->post();
        $project = null;

        $post = Post::query()->with(["comments.user", "category_post", "user"])->where(
            function ($query) use ($last_id) {
                $query->where("enable", 1);
                $query->where("last_id", $last_id);
                $query->where("disable_comments", 0);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {

                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();
        if ($post) {
            if ($post->category_post->isprivate == 1) {
                $post->category_post->load("my_access");
                if (!(isset($post->category_post->my_access) and ($post->category_post->my_access->onlyread == 1 || $post->category_post->my_access->isadmin == 1))) {
                    return array(
                        'type' => 'error',
                        'error_key' => 'not_have_access_to_post',
                        'message' => 'Пост не найден!',
                    );
                }
            }
        }

        if (is_null($post)) {
            return array('type' => 'error', 'error_key' => 'post_not_found', 'message' => 'Пост не найден!');
        }
        if (!(isset($newComment['comment']) and is_string($newComment['comment']) and strlen(
                $newComment['comment']
            ) > 0)) {
            return array(
                'type' => 'error',
                'error_key' => 'comment_is_empty',
                'message' => 'Вы не заполнили комментарий!',
            );
        }

        $comment = new PostComment();
        $comment->user_id = $user_id;
        $comment->comment = strip_tags($newComment['comment']);
        $comment->post_id = $post->last_id;
        $comment->enable = 1;
        $comment->save();
        $comment->load("user");

        // Notify::createSendComment($comment, $project);


        return array('type' => 'success', 'comment' => $comment);


    }


    function show_post($id)
    {

        $post = Post::query()->with(
            ["my_access", "comments.user", "comments.comments.user", "category_post", "user"]
        )->where(
            function ($query) use ($id) {
                $query->where("enable", 1);
                $query->where("last_id", $id);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();

        if ($post) {

            if ($post->category_post->isprivate == 1) {

                if (!(isset($post->my_access) and ($post->my_access->onlyread == 1 || $post->my_access->isadmin == 1))) {
                    return array(
                        'type' => 'error',
                        'error_key' => 'not_access_to_post',
                        'message' => 'Пост не найден!',
                    );
                }
            }
        }


        if ($post) {
            return array('type' => 'success', 'post' => $post);
        }

        return array('type' => 'error', 'error_key' => 'post_not_found', 'message' => 'Запись не найдена!');
    }

    function all($limit, $offset)
    {
        if (!(isset($limit) and is_numeric($limit) and $limit > 0)) {
            $limit = 10;
        }
        if (!(isset($offset) and is_numeric($offset) and $offset >= 0)) {
            $offset = 10;
        }

        $available_categorys = array();
        if (Auth::guard("api")->check()) {
            $user = Auth::guard("api")->user();
            $categorys = BlogCategoryAccess::query()->with("category")->where("user_id", $user->last_id)->whereHas(
                "category",
                function ($query) {
                    $query->where("isprivate", 1);

                }
            )->get();
            if (count($categorys)) {
                foreach ($categorys as $cat) {
                    if ($cat->onlyread == 1) {
                        $available_categorys[] = $cat->category_id;
                    }
                }
            }

        }


        $posts = Post::query()->with("category_post")->where(
            function ($query) use ($available_categorys) {
                $query->where("enable", 1);

                if (count($available_categorys)) {
                    $query->WhereHas(
                        "category_post",
                        function ($query_cat) {
                            $query_cat->where("isprivate", 0);
                            $query_cat->where("enable", 1);
                        }
                    );
                    $query->orWhereIn("category", $available_categorys);
                } else {
                    $query->WhereHas(
                        "category_post",
                        function ($query_cat) {
                            $query_cat->where("isprivate", 0);
                            $query_cat->where("enable", 1);
                        }
                    );
                }
            }
        )->orderByDesc("created_at")->limit($limit)->offset($offset)->get();
        $count = Post::query()->with("category_post")->where(
            function ($query) {
                $query->where("enable", 1);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->count();

        return array('count' => $count, 'posts' => $posts);


    }

    function setEnable($last_id)
    {
        $user = request()->user();
        $new_post = Post::query()->with(["category_post", "user"])->where(
            function ($query) use ($last_id, $user) {

                $query->where("last_id", $last_id);
                $query->where("user_id", $user->last_id);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->first();
        if (!$new_post) {
            return array('type' => 'error', 'message' => 'Пост не найден!');
        }
        if ($new_post->enable == 0) {
            $new_post->enable = 1;
        } else {
            $new_post->enable = 0;
        }
        $new_post->save();

        return array('type' => 'success', 'post' => $new_post);
    }

    function addPost()
    {
        $user = request()->user();
        $form = request()->post();


        $category = null;
        if (isset($form['category']) and is_numeric($form['category'])) {
            $category = BlogCategory::query()->with("my_access")->where("enable", 1)->where(
                "last_id",
                $form['category']
            )->first();

            if (!$category) {
                return array(
                    'type' => 'error',
                    'error_key' => 'not_set_category',
                    'message' => 'Вы не указали Категорию',
                );
            }


            if (!($category->ispublic == 1)) {

                if (!(isset($category->my_access) and (isset($category->my_access->write) and $category->my_access->write == 1) or (isset($category->my_access->isadmin) and $category->my_access->isadmin == 1))) {
                    return array(
                        'type' => 'error',
                        'error_key' => 'not_access_to_publish_category',
                        'message' => 'У вас нет доступа для публикации в категорию '.$category->title,
                    );
                }
            }

        }

        if (!isset($category)) {
            return array('type' => 'error', 'error_key' => 'not_set_category', 'message' => 'Вы не указали Категорию');
        }


        if (!(isset($form['json']['blocks']) and count($form['json']['blocks']) > 0)) {
            return array('type' => 'error', 'error_key' => 'text_not_filled', 'message' => 'Вы не ввели текст поста');
        }


        if (isset($form['json']['blocks']) and count($form['json']['blocks']) > 0) {
            foreach ($form['json']['blocks'] as $key => $block) {

                if ($block['type'] == "image" and ((isset($block['data']['caption']) and mb_strlen(
                                $block['data']['caption']
                            ) == 0) or (isset($block['data']['caption']) and is_null($block['data']['caption'])))) {
                    unset($block['data']['caption']);
                }
                $form['json']['blocks'][$key] = $block;
            }

        }


        $short = array();
        $short = $form['json'];
        $short['blocks'] = array();

        $result_config = \editjs\models\BlocksModel::getConfig();


        try {
            $editor = new EditorJS(json_encode($form['json']), $result_config['config']);
        } catch (\Exception $e) {
            return array(
                'type' => 'error',
                'error_key' => 'text_not_filled',
                'message' => $e->getMessage(),
            );

        }


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

        $new_post = new Post();
        if (isset($form['last_id']) and is_numeric($form['last_id'])) {
            $new_post = Post::query()->with(["category_post", "user"])->where(
                function ($query) use ($form, $user) {
                    $query->where("enable", 1);
                    $query->where("last_id", $form['last_id']);
                    $query->where("user_id", $user->last_id);
                    $query->whereHas(
                        "category_post",
                        function ($query_cat) {
                            $query_cat->where("enable", 1);
                        }
                    );
                }
            )->orderByDesc("created_at")->limit(6)->first();
            if (!$new_post) {
                return array('type' => 'error', 'error_key' => 'post_not_found', 'message' => 'Пост не найден!');
            }
        }
        $new_post->enable = 1;
        $new_post->user_id = $user->last_id;
        $new_post->category = $category->last_id;
        $new_post->short = $short;
        $new_post->content = $content;
        $new_post->save();

        return array('type' => 'success', 'post_id' => $new_post->last_id);

    }
}