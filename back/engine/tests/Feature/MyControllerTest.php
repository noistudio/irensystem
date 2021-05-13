<?php

namespace Tests\Feature;

use App\About;
use App\Comment;
use App\Invoice;
use App\Project;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class MyControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendOfferWithSubCommentAndChoose()
    {


        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $this->actingAs($client, 'api');

        $response = $this->json(
            'POST',
            '/api/setaccount',
            array('account' => 'тут какие то особские реквизитцы'),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',


            ]
        );


    }
}
