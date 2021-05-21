<?php

namespace Tests\Feature;

use App\About;
use App\Comment;
use App\Invoice;
use App\PortfolioCategory;
use App\Project;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class PortfolioControllerTest extends TestCase
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

        $portfolio_category = PortfolioCategory::query()->first();

        $response = $this->json(
            'GET',
            '/api/portfolio/categorys',
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                '*' => ["last_id"],


            ]
        );


        $new_portfolio = [
            'date_start' => date('Y-m-d'),
            'date_end' => date('Y-m-d'),
            'category' => $portfolio_category->last_id,
            'json' => [
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => ['text' => 'TEST LA LA', 'level' => 2],
                    ],
                    [
                        'type' => 'image',
                        'data' => [
                            'withBorder' => false,
                            'withBackground' => false,
                            'stretched' => false,
                            'file' => ['url' => 'https://get.wallhere.com/photo/sunlight-landscape-garden-nature-park-tower-tree-flower-landmark-196671.jpg'],
                        ],
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
            '/api/portfolio/add',
            $new_portfolio,
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        var_dump($response->json());
        $response->assertJsonStructure(
            [
                'type',
                'last_id',
                'message',


            ]
        );

        $resp_json = $response->json();
        $work_id = $resp_json['last_id'];

        $response = $this->json(
            'GET',
            '/api/portfolio/all',
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                '*' => ["last_id"],


            ]
        );


        $update_portfolio = [
            'date_start' => date('Y-m-d'),
            'date_end' => date('Y-m-d'),
            'category' => $portfolio_category->last_id,
            'last_id' => $work_id,
            'json' => [
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => ['text' => 'TEST LA LA', 'level' => 2],
                    ],
                    [
                        'type' => 'image',
                        'data' => [
                            'withBorder' => false,
                            'withBackground' => false,
                            'stretched' => false,
                            'file' => ['url' => 'https://get.wallhere.com/photo/sunlight-landscape-garden-nature-park-tower-tree-flower-landmark-196671.jpg'],
                        ],
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
            '/api/portfolio/add',
            $update_portfolio,
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'last_id',
                'message',


            ]
        );

        $response = $this->json(
            'GET',
            '/api/portfolio/delete/'.$work_id,
            array(),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',


            ]
        );


    }
}
