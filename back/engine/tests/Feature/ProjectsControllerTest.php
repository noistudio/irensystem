<?php


namespace Tests\Feature;

use App\About;
use App\BlogCategory;
use App\Comment;
use App\Invoice;
use App\PortfolioCategory;
use App\Post;
use App\Project;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class ProjectsControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testStats()
    {

        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $this->actingAs($user, 'api');

        $response = $this->json(
            'GET',
            '/api/projects/stats',
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'freelance',
                'inwork',
                'finish',


            ]
        );
    }

    public function testAdd()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($client, 'api');

        $new_task = [

            'category' => $project->category_id,
            'json' => [
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => ['text' => 'TEST LA LA SUUUPER PROJECT!'],
                    ],
                    [
                        'type' => 'image',
                        'data' => ['file' => ['url' => 'https://get.wallhere.com/photo/sunlight-landscape-garden-nature-park-tower-tree-flower-landmark-196671.jpg']],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => ['text' => 'SUPER MEGA TEXT'],
                    ],
                ],
            ],
        ];

        $response = $this->json(
            'POST',
            '/api/projects/add',
            $new_task,
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );


        $response->assertJsonStructure(
            [
                'type',
                'project_id',


            ]
        );

    }

    public function testEdit()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($user, 'api');

        $new_task = [


            'json' => [
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => ['text' => 'TEST LA LA'],
                    ],
                    [
                        'type' => 'image',
                        'data' => ['file' => ['url' => 'https://get.wallhere.com/photo/sunlight-landscape-garden-nature-park-tower-tree-flower-landmark-196671.jpg']],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => ['text' => 'SUPER MEGA TEXT'],
                    ],
                ],
            ],
        ];

        $response = $this->json(
            'POST',
            '/api/projects/edit_project/'.$project->last_id,
            $new_task,
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'project_id',


            ]
        );

    }

    public function testAddTask()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($user, 'api');

        $new_task = [


            'json' => [
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => ['text' => 'TEST LA LA'],
                    ],
                    [
                        'type' => 'image',
                        'data' => ['file' => ['url' => 'https://get.wallhere.com/photo/sunlight-landscape-garden-nature-park-tower-tree-flower-landmark-196671.jpg']],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => ['text' => 'SUPER MEGA TEXT'],
                    ],
                ],
            ],
        ];

        $response = $this->json(
            'POST',
            '/api/projects/addtask/'.$project->last_id,
            $new_task,
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'tasks',


            ]
        );

    }

    public function testGet()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($user, 'api');

        $response = $this->json(
            'GET',
            '/api/projects/get/'.$project->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',


            ]
        );

    }

    public function testChangeStatus()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($user, 'api');

        $response = $this->json(
            'GET',
            '/api/projects/newstatus/'.$project->last_id."/".$project->status,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'new_status',


            ]
        );

    }

    public function testInvoiceWithConfirm()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $this->actingAs($user, 'api');
        $response = $this->json(
            'POST',
            '/api/projects/addinvoice/'.$project->last_id,
            array(
                'user_id' => $project->client_id,
                'type' => 'client',
                'sum' => 1000,
                'currency' => 'RUB',
                'title' => 'Описание!',
            ),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',
                'invoice_id',


            ]
        );
        $invoice_id = $response->json('invoice_id');

        $this->actingAs($client, 'api');
        $response = $this->json(
            'GET',
            '/api/projects/approveinvoice/'.$invoice_id.'/'.$project->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );


        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',


            ]
        );


        $response = $this->json(
            'GET',
            '/api/projects/completeinvoice/'.$invoice_id.'/'.$project->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',


            ]
        );


    }

    public function testInvoiceWithRemove()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $this->actingAs($user, 'api');
        $response = $this->json(
            'POST',
            '/api/projects/addinvoice/'.$project->last_id,
            array(
                'user_id' => $project->client_id,
                'type' => 'client',
                'sum' => 1000,
                'currency' => 'RUB',
                'title' => 'Описание!',
            ),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',
                'invoice_id',


            ]
        );
        $invoice_id = $response->json('invoice_id');

        $response = $this->json(
            'GET',
            '/api/projects/removeinvoice/'.$invoice_id.'/'.$project->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',
                'role',
                'project',


            ]
        );


    }

    public function testAll()
    {

        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $this->actingAs($user, 'api');

        $response = $this->json(
            'GET',
            '/api/projects/all',
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'statuses',
                'projects',


            ]
        );
    }

}