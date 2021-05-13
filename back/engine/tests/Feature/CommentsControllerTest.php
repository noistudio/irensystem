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

class CommentsControllerTest extends TestCase
{

  
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendComment()
    {


        $user = \App\User::query()->where("isdeveloper", 1)->first();

        $project = Project::query()->where("developer_id", $user->last_id)->first();


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

        $response->assertJsonStructure(
            [
                'type',
                'comment' => [
                    'last_id',
                    "created_at",
                    "updated_at",
                    "comment",
                    "user_id",
                    "project_id",
                ],


            ]
        );

        $comment = Comment::query()->where("project_id", $project->last_id)->first();
        $response = $this->json(
            'POST',
            '/api/comments/sendsub/'.$project->last_id."/".$comment->last_id,
            array(
                'comment' => 'super mega check!!',
            )
        );

        $response->assertJsonStructure(
            [
                'type',
                'comments',


            ]
        );


    }
}
