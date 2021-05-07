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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = $request->user();
    $user->makeVisible("api_token");
    return $user;
});

Route::post('login', 'UserController@login')->name('login');
Route::any('about', 'About@index');
Route::any('categorys/all', 'Categorys@all')->name('categorys_all');
Route::get("editor/fetchUrl", "Editor@fetchUrl");
Route::get('developer/{username}', 'Developer@get');
Route::get('developer/reviews/{username}', 'Developer@reviews');
Route::get('developer/work/{username}/{work_id}', 'Developer@getWork');
Route::middleware(['auth:api'])->group(function () {

    Route::get("portfolio/categorys", "Portfolio@categorys");
    Route::get("portfolio/all", "Portfolio@all");
    Route::get('portfolio/delete/{id}', 'Portfolio@delete');
    Route::post("portfolio/add", "Portfolio@add");

    Route::post("editor/uploadImage", "Editor@uploadImage");
    Route::post("editor/uploadFile", "Editor@uploadFile");
    Route::post("uploadavatar", "MyController@uploadAvatar");
    Route::post("setaccount", "MyController@setAccount");
    Route::post("notify/all", "Notify@all");
    Route::get("notify/count", "Notify@count");
    Route::get("notify/readall", "Notify@readall");
    Route::get("notify/setread/{id}", "Notify@setread");
    Route::get("notify/remove/{id}", "Notify@remove");
    Route::post("freelance/send/{project_id}", "Freelance@send");
    Route::post("freelance/sendcomment/{project_id}/{offer_id}", "Freelance@sendComment");
    Route::get("freelance/choose/{project_id}/{offer_id}", "Freelance@choose");
    Route::post('projects/add', 'Projects@add');
    Route::post("projects/edit_project/{id}", "Projects@edit_project");
    Route::get("projects/stats", "Projects@stats");
    Route::get("projects/newstatus/{project_id}/{status_id}", "Projects@setNewStatus");
    Route::post("projects/addtask/{id}", "Projects@addTask");
    Route::post("users/add/{id}", "Users@add");
    Route::get("users/delete/{id}/{user_id}", "Users@delete");
    Route::get('projects/all', 'Projects@all');
    Route::get('projects/get/{id}', 'Projects@get');
    Route::post('projects/addinvoice/{id}', 'Projects@addInvoice');
    Route::get('projects/removeinvoice/{id}/{project_id}', 'Projects@removeInvoice');
    Route::get('projects/approveinvoice/{id}/{project_id}', 'Projects@approveInvoice');
    Route::get('projects/completeinvoice/{id}/{project_id}', 'Projects@completeInvoice');
    Route::post('comments/add/{project_id}', 'Comments@add');
    Route::post("comments/sendsub/{project_id}/{comment_id}", "Comments@sendSubComment");
    Route::get('invoices/all', 'Invoices@all');
    Route::post('invoices/sendreview/{id}', 'Invoices@sendreview');
    Route::get('invoices/get/{id}', 'Invoices@get');
    Route::get('invoices/setpay/{id}', 'Invoices@setPay');
    Route::get('invoices/finishpay/{id}', 'Invoices@finishPay');
    Route::get("invoices/stats", "Invoices@stats");

});

