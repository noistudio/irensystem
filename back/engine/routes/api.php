<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        $user = $request->user();
        $user->makeVisible("api_token");

        return $user;
    }
);

Route::post('login', 'UserController@login')->name('login');
Route::any('user_ban', 'UserController@user_ban')->name('user_ban');
Route::any('about', 'About@index');
Route::get('pages', 'Pages@all');
Route::get('page/{id}', 'Pages@show')->where('id', '[0-9]+');;

Route::get('download/{code}', 'Download@file')->name("download");
Route::get('blog/all/{limit}/{offset}', 'Posts@all')->where(['limit' => '[0-9]+', 'offset' =>  '[0-9]+']);
Route::get('blog/post/{id}', 'Posts@show_post')->where('id', '[0-9]+');
Route::any('categorys/all', 'Categorys@all')->name('categorys_all');
Route::get("editor/fetchUrl", "Editor@fetchUrl");
Route::get('developer/{username}', 'Developer@get');
Route::get('developer/reviews/{username}', 'Developer@reviews');
Route::get('developer/work/{username}/{work_id}', 'Developer@getWork')->where('work_id', '[0-9]+');
Route::middleware(['auth:api'])->group(
    function () {

        Route::get("blog/delcomment/{comment_id}/{post_id}", "Posts@delete_comment")->where(['comment_id' => '[0-9]+', 'post_id' =>  '[0-9]+']);
        Route::get("blog/delsubcomment/{subcomment_id}/{comment_id}/{post_id}", "Posts@delete_sub_comment")->where(['subcomment_id' => '[0-9]+','comment_id' => '[0-9]+', 'post_id' =>  '[0-9]+']);
        Route::any('blog/categorys', 'Posts@categorys')->name('blog.categorys');
        Route::post('blog/addpost', 'Posts@addPost');
        Route::post("blog/comments/sendsub/{post_id}/{comment_id}", "Posts@sendSubComment")->where(['post_id' => '[0-9]+', 'comment_id' =>  '[0-9]+']);
        Route::post("blog/commentadd/{last_id}", "Posts@addComment")->where('last_id', '[0-9]+');
        Route::get('blog/enable/{last_id}', 'Posts@setEnable')->where('last_id', '[0-9]+');;
        Route::get("portfolio/categorys", "Portfolio@categorys");
        Route::get("portfolio/all", "Portfolio@all");
        Route::get('portfolio/delete/{id}', 'Portfolio@delete')->where('id', '[0-9]+');;
        Route::post("portfolio/add", "Portfolio@add");

        Route::post("editor/uploadImage", "Editor@uploadImage");
        Route::post("editor/uploadFile", "Editor@uploadFile");
        Route::post("uploadavatar", "MyController@uploadAvatar");
        Route::post("setaccount", "MyController@setAccount");
        Route::post("notify/all", "Notify@all");
        Route::get("notify/count", "Notify@count");
        Route::get("notify/readall", "Notify@readall");
        Route::get("notify/setread/{id}", "Notify@setread")->where('id', '[0-9]+');
        Route::get("notify/remove/{id}", "Notify@remove")->where('id', '[0-9]+');
        Route::post("freelance/send/{project_id}", "Freelance@send")->where('project_id', '[0-9]+');
        Route::post("freelance/sendcomment/{project_id}/{offer_id}", "Freelance@sendComment")->where(['project_id' => '[0-9]+', 'offer_id' =>  '[0-9]+']);
        Route::get("freelance/choose/{project_id}/{offer_id}", "Freelance@choose")->where(['project_id' => '[0-9]+', 'offer_id' =>  '[0-9]+']);
        Route::post('projects/add', 'Projects@add');
        Route::post("projects/edit_project/{id}", "Projects@edit_project")->where('id', '[0-9]+');
        Route::get("projects/stats", "Projects@stats");
        Route::get("projects/newstatus/{project_id}/{status_id}", "Projects@setNewStatus")->where(['project_id' => '[0-9]+', 'status_id' =>  '[0-9]+']);
        Route::post("projects/addtask/{id}", "Projects@addTask")->where('id', '[0-9]+');
        Route::post("users/add/{id}", "Users@add")->where('id', '[0-9]+');
        Route::get("users/delete/{id}/{user_id}", "Users@delete")->where(['id' => '[0-9]+', 'user_id' =>  '[0-9]+']);
        Route::get('projects/all', 'Projects@all');
        Route::get('projects/get/{id}', 'Projects@get')->where('id', '[0-9]+');
        Route::post('projects/addinvoice/{id}', 'Projects@addInvoice')->where('id', '[0-9]+');
        Route::get('projects/removeinvoice/{id}/{project_id}', 'Projects@removeInvoice')->where(['id' => '[0-9]+', 'project_id' =>  '[0-9]+']);
        Route::get('projects/approveinvoice/{id}/{project_id}', 'Projects@approveInvoice')->where(['id' => '[0-9]+', 'project_id' =>  '[0-9]+']);
        Route::get('projects/completeinvoice/{id}/{project_id}', 'Projects@completeInvoice')->where(['id' => '[0-9]+', 'project_id' =>  '[0-9]+']);
        Route::post('comments/add/{project_id}', 'Comments@add')->where('project_id', '[0-9]+');
        Route::post("comments/sendsub/{project_id}/{comment_id}", "Comments@sendSubComment")->where(['project_id' => '[0-9]+', 'comment_id' =>  '[0-9]+']);
        Route::get('invoices/all', 'Invoices@all');
        Route::post('invoices/sendreview/{id}', 'Invoices@sendreview')->where('id', '[0-9]+');
        Route::get('invoices/get/{id}', 'Invoices@get')->where('id', '[0-9]+');
        Route::get('invoices/setpay/{id}', 'Invoices@setPay')->where('id', '[0-9]+');
        Route::get('invoices/finishpay/{id}', 'Invoices@finishPay')->where('id', '[0-9]+');
        Route::get("invoices/stats", "Invoices@stats");

    }
);

