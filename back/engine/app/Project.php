<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    public $timestamps = true;
    public $table = "proj_projects";
    public $primaryKey = "last_id";


    public function getUsers()
    {
        if ($this->developer_id == 0) {
            return array();
        }
        $my_id = request()->user()->last_id;
        $users_id = array();


        $proj_users = ProjectUser::query()->where("project_id", $this->last_id)->get();
        $proj_users_id = $proj_users->pluck('user_id')->toArray();

        $users_id = $proj_users_id;
        $users_id[] = $this->client_id;
        $users_id[] = $this->developer_id;


        $users = User::query()->whereIn("last_id", $users_id)->get();
        $new_result = array();
        if (count($users)) {
            foreach ($users as $key => $user) {
                if ($user->last_id == $this->client_id) {
                    $user->role = "client";
                }
                if ($user->last_id == $this->developer_id) {
                    $user->role = "developer";
                } else if (count($proj_users) > 0) {
                    foreach ($proj_users as $pro_user) {
                        if ($pro_user->user_id == $user->last_id) {
                            $user->role = "spectator";
                            $user->isapprove = $pro_user->isapprove;
                        }
                    }

                }
                $new_result['last_id_' . $user->last_id] = $user;
            }
        }
        return $new_result;

    }

    public function client()
    {
        return $this->hasOne(User::class, "last_id", "client_id");
    }

    public function spectator()
    {
        return $this->hasOne(ProjectUser::class, "project_id", "last_id")->where("user_id", request()->user()->last_id)->where("isapprove", 1);
    }

    public function invoices()
    {
        $me = request()->user();
        return $this->hasMany(ProjectInvoice::class, "project_id", "last_id")->where(function ($sub_query) use ($me) {
            $sub_query->orWhere("client_id", $me->last_id);

            $sub_query->orWhere("developer_id", $me->last_id);


        });
    }

    public function tasks()
    {
        $me = request()->user();
        return $this->hasMany(Project::class, "main_project_id", "last_id")->where(function ($sub_query) use ($me) {
            $sub_query->orWhere("client_id", $me->last_id);

            $sub_query->orWhere("developer_id", $me->last_id);

            $sub_query->orHas("spectator");
        });
    }

    public function main_project()
    {
        return $this->hasOne(Project::class, "last_id", "main_project_id");
    }

    public function offers()
    {
        return $this->hasMany(ProjectOffer::class, "project_id", "last_id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, "project_id", "last_id");
    }

    public function status_row()
    {
        return $this->hasOne(Status::class, "last_id", "status");
    }

    public function addInvoice($new_invoice)
    {


        $project_invoice = new ProjectInvoice();
        if ($new_invoice['type'] == "client") {
            $project_invoice->developer_id = request()->user()->last_id;
            $project_invoice->client_id = $new_invoice['user_id'];
        } else {
            $project_invoice->client_id = request()->user()->last_id;
            $project_invoice->developer_id = $new_invoice['user_id'];
        }
        $project_invoice->currency = $new_invoice['currency'];
        $project_invoice->project_id = $this->last_id;
        $project_invoice->sum = $new_invoice['sum'];
        $project_invoice->title = $new_invoice['title'];
        if ($new_invoice['type'] == "developer") {
            $project_invoice->is_approve_client = 1;
        }

        $project_invoice->save();

        return $project_invoice;


    }

    static function add($form)
    {


        $new_project = new Project();
        $new_project->start_time = Carbon::now();
        $new_project->name_project = $form['name_project'];
        $new_project->client_id = request()->user()->last_id;
        $new_project->developer_id = 0;
        $new_project->category_id = $form['category']->last_id;
        $new_project->json = json_encode($form['json']);

        $status = Status::query()->where("issearch", 1)->where("enable", 1)->first();
        $new_project->status = $status->last_id;

        $new_project->save();
        return $new_project;


    }

}