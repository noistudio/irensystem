<?php


namespace App\Http\Controllers\Api;


use App\Category;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Notify;
use App\Project;
use App\ProjectInvoice;
use App\ProjectOffer;
use App\ProjectUser;
use App\Status;
use App\User;
use App\UserCategory;
use Carbon\Carbon;

class Projects extends Controller
{


    public function stats()
    {
        $result = array();
        $result['freelance'] = 0;
        $result['inwork'] = 0;
        $result['finish'] = 0;

        $me = request()->user();

        $develops_category = array();
        if ($me->isdeveloper == 1) {
            $develops_category = UserCategory::query()->where("user_id", $me->last_id)->get()->pluck(
                'category_id'
            )->toArray();

        }


        $user_id = request()->user()->last_id;
        $result['freelance'] = Project::query()->where(
            function ($query) use ($me, $develops_category) {

                $query->orWhere(
                    function ($sub_query) use ($me, $develops_category) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        if ($me->isdeveloper == 1) {
                            $sub_query->orWhere("developer_id", $me->last_id);
                        }
                        $sub_query->orHas("spectator");
                    }
                );
                if (count($develops_category) > 0) {
                    $query->orWhere(
                        function ($sub_query2) use ($me, $develops_category) {
                            $sub_query2->where("developer_id", 0);
                            $sub_query2->where("category_id", $develops_category);
                        }
                    );
                }


            }
        )->where("developer_id", 0)->count();

        $result['inwork'] = Project::query()->where(
            function ($query) use ($me, $develops_category) {

                $query->orWhere(
                    function ($sub_query) use ($me, $develops_category) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        if ($me->isdeveloper == 1) {
                            $sub_query->orWhere("developer_id", $me->last_id);
                        }
                        $sub_query->orHas("spectator");
                    }
                );
                if (count($develops_category) > 0) {
                    $query->orWhere(
                        function ($sub_query2) use ($me, $develops_category) {
                            $sub_query2->where("developer_id", 0);
                            $sub_query2->where("category_id", $develops_category);
                        }
                    );
                }


            }
        )->where("developer_id", ">", 0)->where('main_project_id', 0)->count();


        $result['finish'] = Project::query()->where(
            function ($query) use ($me, $develops_category) {

                $query->orWhere(
                    function ($sub_query) use ($me, $develops_category) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        if ($me->isdeveloper == 1) {
                            $sub_query->orWhere("developer_id", $me->last_id);
                        }
                        $sub_query->orHas("spectator");
                    }
                );
                if (count($develops_category) > 0) {
                    $query->orWhere(
                        function ($sub_query2) use ($me, $develops_category) {
                            $sub_query2->where("developer_id", 0);
                            $sub_query2->where("category_id", $develops_category);
                        }
                    );
                }


            }
        )->where("developer_id", ">", 0)->where('main_project_id', 0)->whereHas(
            'status_row',
            function ($query) {
                $query->where('isfinish', '1');
            }
        )->count();

        return $result;


    }

    public function all()
    {

        $me = request()->user();

        $develops_category = array();
        if ($me->isdeveloper == 1) {
            $develops_category = UserCategory::query()->where("user_id", $me->last_id)->get()->pluck(
                'category_id'
            )->toArray();

        }


        $user_id = request()->user()->last_id;
        $projects = Project::query()->with('status_row')->where(
            function ($query) use ($me, $develops_category) {

                $query->orWhere(
                    function ($sub_query) use ($me, $develops_category) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        if ($me->isdeveloper == 1) {
                            $sub_query->orWhere("developer_id", $me->last_id);
                        }
                        $sub_query->orHas("spectator");
                    }
                );
                if (count($develops_category) > 0) {
                    $query->orWhere(
                        function ($sub_query2) use ($me, $develops_category) {
                            $sub_query2->where("developer_id", 0);
                            $sub_query2->where("category_id", $develops_category);
                        }
                    );
                }


            }
        )->where("main_project_id", 0)->orderByDesc("created_at")->orderByDesc("updated_at")->get();

        $statuses = Status::query()->where(
            function ($query) {
                $query->orWhere("issearch", 1);
                $query->orWhere("iswork", 1);
                $query->orWhere("isfinish", 1);
            }
        )->get();


        return array('type' => 'success', 'statuses' => $statuses, 'projects' => $projects);
    }

    public function completeInvoice($id, $project_id)
    {
        $user_id = request()->user()->last_id;
        $me = request()->user();
        $project = Project::query()->where(
            function ($query) use ($me) {

                $query->orWhere(
                    function ($sub_query) use ($me) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        $sub_query->orWhere("developer_id", $me->last_id);

                        $sub_query->orHas("spectator");
                    }
                );


            }
        )->where("last_id", $project_id)->first();
        if (!($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден, либо вы не являетесь клиентом!');
        }
        $project_invoice = ProjectInvoice::query()->where('client_id', $me->last_id)->where(
            "project_id",
            $project->last_id
        )->where("last_id", $id)->where("is_finish", 0)->where("is_approve_client", 1)->first();
        if (!$project_invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }
        $project_invoice->is_finish = 1;
        $project_invoice->save();

        $new_invoice = new Invoice();
        $new_invoice->sum = $project_invoice->sum;
        $new_invoice->currency = $project_invoice->currency;
        $new_invoice->client_id = $project_invoice->client_id;
        $new_invoice->developer_id = $project_invoice->developer_id;
        $new_invoice->title = $project_invoice->title;
        $new_invoice->save();
        $project_invoice->final_invoice_id = $new_invoice->last_id;
        $project_invoice->save();

        Notify::createCompleteInvoice($new_invoice, $project_invoice, $project);


        return $this->get($project->last_id);
    }

    public function setNewStatus($project_id, $status_id)
    {
        $user_id = request()->user()->last_id;
        $me = request()->user();
        $project = Project::query()->where(
            function ($query) use ($me) {

                $query->orWhere(
                    function ($sub_query) use ($me) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        $sub_query->orWhere("developer_id", $me->last_id);


                    }
                );


            }
        )->where("developer_id", ">", 0)->where("last_id", $project_id)->first();
        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');


        }
        $status = null;
        if (isset($status_id) and is_numeric($status_id)) {
            $status = Status::query()->where(
                function ($query) use ($project, $me) {
                    $query->orWhere("iswork", 1);
                    $query->orWhere("isfinish", 1);

                }
            )->where("last_id", $status_id)->first();
        }
        if (!$status) {
            return array('type' => 'error', 'message' => 'Статус не найден!');
        }

        $project->status = $status->last_id;
        $project->save();

        Notify::createNewStatus($status, $project);

        return array('type' => 'success');


    }


    public function approveInvoice($id, $project_id)
    {

        $user_id = request()->user()->last_id;
        $me = request()->user();
        $project = Project::query()->where(
            function ($query) use ($me) {

                $query->orWhere(
                    function ($sub_query) use ($me) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        $sub_query->orWhere("developer_id", $me->last_id);

                        $sub_query->orHas("spectator");
                    }
                );


            }
        )->where("last_id", $project_id)->first();
        if (!($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден, либо вы не являетесь клиентом!');
        }
        $project_invoice = ProjectInvoice::query()->where('client_id', $me->last_id)->where(
            "project_id",
            $project->last_id
        )->where("last_id", $id)->where("is_approve_client", 0)->first();
        if (!$project_invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }
        $project_invoice->is_approve_client = 1;
        $project_invoice->save();
        Notify::createApproveInvoice($project_invoice, $project);

        return $this->get($project->last_id);
    }

    public function removeInvoice($id, $project_id)
    {


        $user_id = request()->user()->last_id;
        $me = request()->user();
        $project = Project::query()->with(
            "main_project",
            "client",
            "tasks",
            "tasks.status_row",
            "tasks.client",
            "invoices",
            "invoices.final",
            "comments",
            "comments.comments",
            "comments.comments.user",
            "comments.user",
            "status_row",
            "offers",
            "offers.comments",
            "offers.comments.user",
            "offers.developer"
        )->where(
            function ($query) use ($me) {

                $query->orWhere(
                    function ($sub_query) use ($me) {
                        $sub_query->orWhere("client_id", $me->last_id);
                        $sub_query->orWhere("developer_id", $me->last_id);

                        $sub_query->orHas("spectator");
                    }
                );


            }
        )->where("last_id", $project_id)->first();
        if (!($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден, либо вы не являетесь разработчиком!');
        }
        $project_invoice = ProjectInvoice::query()->where("developer_id", $me->last_id)->where(
            "project_id",
            $project->last_id
        )->where("last_id", $id)->where("is_approve_client", 0)->first();
        if (!$project_invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }
        $project_invoice->delete();

        return $this->get($project->last_id);
    }

    public function addInvoice($id)
    {
        $user_id = request()->user()->last_id;
        $project = Project::query()->where("last_id", $id)->where(
            function ($query) use ($user_id) {

                $query->where("developer_id", $user_id);
            }
        )->first();
        if (!($project)) {
            return array('type' => 'error', 'message' => 'Проект не найден, либо вы не являетесь разработчиком!');
        }
        $new_invoice = request()->post();
        $user_invoice = null;
        if (isset($new_invoice['user_id']) and is_numeric(
                $new_invoice['user_id']
            ) and $new_invoice['user_id'] != $user_id) {
            $user_invoice = User::query()->where('last_id', $new_invoice['user_id'])->first();
        }

        if (!$user_invoice) {
            return array('type' => 'error', 'message' => 'Вы не указали пользователя к кому будет привязан счет!');
        }

        $types_available = array('client', 'developer');
        if (!(isset($new_invoice['type']) and in_array($new_invoice['type'], $types_available))) {
            return array('type' => 'error', 'message' => 'Вы не выбрали ТИП счета');
        }

        if ($new_invoice['type'] == "developer") {
            $users = $project->getUsers();
            if (!(isset($users['last_id_'.$user_invoice->last_id]) and $users['last_id_'.$user_invoice->last_id]->role == "spectator")) {
                return array('type' => 'error', 'message' => 'Разработчиком может выступать только наблюдатель!');
            }
        }


        if (!(isset($new_invoice['sum']) and is_numeric($new_invoice['sum']) and (int)$new_invoice['sum'] > 0)) {
            return array('type' => 'error', 'message' => 'Вы не указали сумму');
        }

        if (!(isset($new_invoice['currency']) and is_string($new_invoice['currency']) and strlen(
                    $new_invoice['currency']
                )) > 0) {
            return array('type' => 'error', 'message' => 'Вы не указали валюту');
        }

        if (!(isset($new_invoice['title']) and is_string($new_invoice['title']) and strlen(
                $new_invoice['title']
            ) > 0)) {
            return array('type' => 'error', 'message' => 'Вы не указали Описание услуги');
        }

        $project_invoice = $project->addInvoice($new_invoice);
        Notify::createAddInvoice($project_invoice, $project);

        return $this->get($project->last_id);

    }

    public function get($id)
    {


        $me = request()->user();
        $user_id = request()->user()->last_id;

        $project_user = ProjectUser::query()->where("project_id", $id)->where("user_id", $me->last_id)->where(
            "isapprove",
            0
        )->first();
        if ($project_user) {
            $project_user->isapprove = 1;
            $project_user->save();
        }

        $develops_category = array();
        if ($me->isdeveloper == 1) {
            $develops_category = UserCategory::query()->where("user_id", $me->last_id)->get()->pluck(
                'category_id'
            )->toArray();

        }


        $project = Project::query()->with(
            "main_project",
            "client",
            "tasks",
            "tasks.status_row",
            "tasks.client",
            "invoices",
            "invoices.final",
            "comments",
            "comments.comments",
            "comments.comments.user",
            "comments.user",
            "status_row",
            "offers",
            "offers.comments",
            "offers.comments.user",
            "offers.developer"
        )->where(
            function ($query) use ($me, $develops_category) {

                $query->orWhere(
                    function ($sub_query) use ($me, $develops_category) {
                        $sub_query->orWhere("client_id", $me->last_id);

                        $sub_query->orWhere("developer_id", $me->last_id);

                        $sub_query->orHas("spectator");
                    }
                );
                if (count($develops_category) > 0) {
                    $query->orWhere(
                        function ($sub_query2) use ($me, $develops_category) {
                            $sub_query2->where("developer_id", 0);
                            $sub_query2->where("category_id", $develops_category);
                        }
                    );
                }


            }
        )->where("last_id", $id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');
        }
        $role = "client";
        if ($project->developer_id == $user_id) {
            $role = "developer";
        }
        if ($project->client_id == $user_id) {
            $role = "client";
        } else {
            if ($project->developer_id == 0 and in_array($project->category_id, $develops_category)) {
                $role = "developer";
            }
        }


        if (isset($project->status_row->issearch) and $project->status_row->issearch == 1) {
            $edit_offer = new \stdClass();
            $edit_offer->price = null;
            $edit_offer->currency = null;
            $edit_offer->date_end = null;
            $edit_offer->comment = null;
            $edit_offer->last_id = null;
            if ($role == "developer") {
                $offer = ProjectOffer::query()->where("project_id", $project->last_id)->where(
                    "developer_id",
                    request()->user()->last_id
                )->first();
                if ($offer) {
                    $edit_offer = $offer;
                }
            }
            $project->edit_offer = $edit_offer;
        }


        $project->statuses = array();
        if ($project->status_row->iswork == 1) {
            $project->statuses = Status::query()->where(
                function ($query) {
                    $query->orWhere("iswork", 1);
                    $query->orWhere("isfinish", 1);
                }
            )->get();
        }
        $users = $project->getUsers();
        if (isset($users['last_id_'.$user_id]) and $users['last_id_'.$user_id]->role == "spectator") {
            $role = "spectator";
        }

        return array('type' => 'success', 'users' => $users, 'role' => $role, 'project' => $project);
    }


    public function addTask($id)
    {
        $user = request()->user();
        $form = request()->post();

        $header = null;
        $image = null;
        $description = "";
        if (isset($form['json']['blocks']) and is_array($form['json']['blocks']) and count(
                $form['json']['blocks']
            ) > 0) {
            foreach ($form['json']['blocks'] as $block) {


                if (isset($block['type']) and $block['type'] == "header" and isset($block['data']) and isset($block['data']['text'])
                    and strlen($block['data']['text']) > 0
                ) {
                    $header = $block['data']['text'];
                    break;
                }


            }
        }


        if ($header == null) {
            return array('type' => 'error', 'message' => 'Вы не указали Название проекта');
        }


        $form['name_project'] = $header;

        $project = Project::query()->where(
            function ($query) use ($user) {
                $query->orWhere("developer_id", $user->last_id);
                $query->orWhere("client_id", $user->last_id);
            }
        )->where("last_id", $id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Исходный проект не найден!');


        }
        $developer = User::query()->where("last_id", $project->developer_id)->first();
        $client = User::query()->where("last_id", $project->client_id)->first();
        if (!($developer and $client)) {
            return array('type' => 'error', 'message' => 'Не найдено!');
        }


        $new_project = new Project();
        $new_project->name_project = $form['name_project'];
        $new_project->category_id = 0;
        $new_project->start_time = Carbon::now();
        $new_project->developer_id = $developer->last_id;
        $new_project->client_id = $client->last_id;
        if (isset($form['developer']) and is_numeric($form['developer'])) {
            $developer = User::query()->where("last_id", $form['developer'])->first();
            $users = $project->getUsers();
            if (!$developer and isset($users['last_id_'.$developer['last_id']]) and $users['last_id_'.$developer['last_id']]->role == "spectator") {

                return array('type' => 'error', 'message' => 'Исполнитель не найден!');
            }
            $new_project->developer_id = $developer->last_id;
            $new_project->client_id = $user->last_id;

        }

        $status = Status::query()->where("iswork", 1)->first();
        if (!$status) {
            return array('type' => 'error', 'message' => 'Статус не найден!');
        }
        $new_project->status = $status->last_id;
        $new_project->json = json_encode($form['json']);
        $new_project->main_project_id = $project->last_id;

        $new_project->save();

        Notify::createAddTask($new_project, $project);

        $tasks = Project::query()->with("client", "status_row")->where('main_project_id', $project->last_id)->where(
            function ($query) use ($user) {
                $query->orWhere("developer_id", $user->last_id);
                $query->orWhere("client_id", $user->last_id);
            }
        )->get();

        return array('type' => 'success', 'tasks' => $tasks);


    }

    public function edit_project($id)
    {
        $user = request()->user();
        $form = request()->post();

        $header = null;
        $image = null;
        $description = "";
        if (isset($form['json']['blocks']) and is_array($form['json']['blocks']) and count(
                $form['json']['blocks']
            ) > 0) {
            foreach ($form['json']['blocks'] as $block) {


                if (isset($block['type']) and $block['type'] == "header" and isset($block['data']) and isset($block['data']['text'])
                    and strlen($block['data']['text']) > 0
                ) {
                    $header = $block['data']['text'];
                    break;
                }


            }
        }


        if ($header == null) {
            return array('type' => 'error', 'message' => 'Вы не указали Название проекта');
        }


        $form['name_project'] = $header;

        $project = Project::query()->where(
            function ($query) use ($user) {
                $query->orWhere("client_id", $user->last_id);
                $query->orWhere('developer_id', $user->last_id);
            }
        )->where("last_id", $id)->first();

        if (!$project) {
            return array('type' => 'error', 'message' => 'Проект не найден!');


        }
        $project->name_project = $form['name_project'];
        $project->json = json_encode($form['json']);
        $project->save();
        Notify::createUpdateProject($project);


        return array('type' => 'success', 'project_id' => $project->last_id);
    }

    public function add()
    {
        $form = request()->post();

        $header = null;
        $image = null;
        $description = "";
        if (isset($form['json']['blocks']) and is_array($form['json']['blocks']) and count(
                $form['json']['blocks']
            ) > 0) {
            foreach ($form['json']['blocks'] as $block) {


                if (isset($block['type']) and $block['type'] == "header" and isset($block['data']) and isset($block['data']['text'])
                    and strlen($block['data']['text']) > 0
                ) {
                    $header = $block['data']['text'];
                    break;
                }


            }
        }


        if ($header == null) {
            return array('type' => 'error', 'message' => 'Вы не указали Название проекта');
        }


        $category = null;
        if (isset($form['category']) and is_integer($form['category'])) {
            $category = Category::query()->where("last_id", $form['category'])->first();
        }
        if (!isset($category)) {
            return array('type' => 'error', 'message' => 'Вы не указали Тип');
        }

        $form['category'] = $category;

        $form['name_project'] = $header;


        $new_project = Project::add($form);

        return array('type' => 'success', 'project_id' => $new_project->last_id);
    }

}