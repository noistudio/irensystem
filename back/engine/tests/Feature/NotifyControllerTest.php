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

class NotifyControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotify()
    {


        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();

        $this->actingAs($user, 'api');
        $response = $this->json(
            'POST',
            '/api/comments/add/'.$project->last_id,
            array(
                'comment' => 'super mega check!!',
            ),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );


        $this->actingAs($client, 'api');

        $response = $this->json(
            'GET',
            '/api/notify/count',
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'count',


            ]
        );


        $response = $this->json(
            'POST',
            '/api/notify/all',
            array('show_only_unread' => false),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                '*' => ["last_id"],


            ]
        );

        $notifys = $response->json();
        $notify_id = $notifys[0]['last_id'];

        $response = $this->json(
            'GET',
            '/api/notify/readall',
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',


            ]
        );


        $response = $this->json(
            'GET',
            '/api/notify/setread/'.$notify_id,
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',


            ]
        );


        $response = $this->json(
            'GET',
            '/api/notify/remove/'.$notify_id,
            array(),
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
