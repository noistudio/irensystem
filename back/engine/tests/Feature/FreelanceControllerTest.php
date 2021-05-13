<?php

namespace Tests\Feature;

use App\About;
use App\Comment;
use App\Project;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class FreelanceControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendOfferWithSubCommentAndChoose()
    {


        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", 0)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();


        $response = $this->json(
            'POST',
            '/api/freelance/send/'.$project->last_id,
            array(
                'price' => '1000',
                'currency' => 'RUB',
                'date_end' => date('Y-m-d'),
                'comment' => 'COMMENT!',
            ),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'offer',


            ]
        );
        $result = $response->json();
        $offer_id = $result['offer']['last_id'];
        $response = $this->json(
            'POST',
            '/api/freelance/sendcomment/'.$project->last_id."/".$offer_id,
            array(

                'comment' => 'SUBCOMMENT!',
            ),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'comments',


            ]
        );

        $this->actingAs($client, 'api');
        $response = $this->json(
            'GET',
            '/api/freelance/choose/'.$project->last_id."/".$offer_id,
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'project',


            ]
        );


    }
}
