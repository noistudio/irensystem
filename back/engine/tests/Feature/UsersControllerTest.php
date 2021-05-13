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

class UsersControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testAddAndDelete()
    {
        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();

        $client = \App\User::query()->where("last_id", $project->client_id)->first();
        $observer = \App\User::query()->where("username", "observer")->first();

        $this->actingAs($user, 'api');

        $response = $this->json(
            'POST',
            '/api/users/add/'.$project->last_id,
            array('username' => 'observer'),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',


            ]
        );


        $response = $this->json(
            'GET',
            '/api/users/delete/'.$project->last_id."/".$observer->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'users',


            ]
        );


    }

}