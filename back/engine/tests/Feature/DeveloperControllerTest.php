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

class DeveloperControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeveloper()
    {


        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();


        $response = $this->json(
            'GET',
            '/api/developer/'.$user->username,
            array(),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'developer',


            ]
        );

        $response_dev = $response->json();

        $response = $this->json(
            'GET',
            '/api/developer/work/'.$user->username.'/'.$response_dev['developer']['portfolio'][0]['last_id'],
            array(),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'developer',
                'work',


            ]
        );

        $response = $this->json(
            'GET',
            '/api/developer/reviews/'.$user->username,
            array(),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'developer',


            ]
        );


    }
}
