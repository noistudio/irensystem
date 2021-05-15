<?php

namespace Tests\Feature;

use App\About;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AboutTableSeeder;

class CategorysControllerTest extends TestCase
{

    // use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {


        $response = $this->json('GET', '/api/categorys/all');

        $response->assertJsonStructure(
            [

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


            ]
        );

    }
}
