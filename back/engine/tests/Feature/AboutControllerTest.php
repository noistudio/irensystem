<?php

namespace Tests\Feature;

use App\About;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class AboutControllerTest extends TestCase
{

    // use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        $result = About::query()->first();

        $response = $this->json('GET', '/api/about');

        $response->assertJsonStructure(
            [
                'type',
                'works' => [
                    "*" => [
                        "last_id",
                        "created_at",
                        "updated_at",
                        "enable",
                        "action",
                        "sort",
                        "_lng",
                        "description",
                        "image",
                        "date_end",
                        "date_start",
                        "name",
                        "category_id",
                        "user_id",
                        "json",

                        "category" => [
                            "last_id",
                            "created_at",
                            "updated_at",
                            "enable",
                            "action",
                            "sort",
                            "_lng",
                            "name",
                        ],
                        "user" => [
                            "last_id",
                            "created_at",
                            "updated_at",
                            "enable",
                            "action",
                            "sort",
                            "_lng",
                            "name",
                            "account",
                            "avatar",
                            "username",
                            "telegram_id",
                            "isdeveloper",
                            "isteam",
                            "job",
                        ],
                    ],
                ],
                'posts' => [
                    "*" => [
                        "last_id",
                        "created_at",
                        "updated_at",
                        "enable",
                        "action",
                        "sort",
                        "_lng",
                        "user_id",
                        "category",
                        "disable_comments",
                        "short",
                        "content",
                        "category_post",
                    ],

                ],
                'teams' => [
                    "*" => [
                        "last_id",
                        "created_at",
                        "updated_at",
                        "enable",
                        "action",
                        "sort",
                        "_lng",
                        "name",
                        "account",
                        "avatar",
                        "username",
                        "telegram_id",
                        "isdeveloper",
                        "isteam",
                        "job",
                    ],
                ],
                'about' => [
                    "last_id",
                    "created_at",
                    "updated_at",
                    "enable",
                    "action",
                    "sort",
                    "_lng",
                    "title",
                    "header",
                    "content",
                ],

                'categorys' => [
                    "*" => [
                        "last_id",
                        "created_at",
                        "updated_at",
                        "enable",
                        "action",
                        "sort",
                        "_lng",
                        "name",
                        "image",
                        "description",
                    ],
                ],
            ]
        );

    }
}
