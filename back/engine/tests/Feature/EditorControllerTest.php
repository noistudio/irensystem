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

class EditorControllerTest extends TestCase
{

    //use RefreshDatabase;


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
            '/api/editor/fetchUrl?url=https://yandex.ru',
            array(),
            [
                'authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'success',
                'meta',


            ]
        );


    }
}
