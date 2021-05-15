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

class InvoicesControllerTest extends TestCase
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
            'GET',
            '/api/invoices/all',
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'user',
                'invoices',


            ]
        );


        $response = $this->json(
            'GET',
            '/api/invoices/stats',
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'rub_developer',
                'rub_client',
                'usd_developer',
                'usd_client',
                'euro_developer',
                'euro_client',


            ]
        );

        $invoice = Invoice::query()->where("client_id", $client->last_id)->first();

        $response = $this->json(
            'GET',
            '/api/invoices/get/'.$invoice->last_id,
            array(),
            [
                'Authorization' => 'Bearer '.$client->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'user',
                'invoice',


            ]
        );

        $response = $this->json(
            'GET',
            '/api/invoices/setpay/'.$invoice->last_id,
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

        $this->actingAs($user, 'api');
        $response = $this->json(
            'GET',
            '/api/invoices/finishpay/'.$invoice->last_id,
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


        $this->actingAs($user, 'api');
        $response = $this->json(
            'POST',
            '/api/invoices/sendreview/'.$invoice->last_id,
            array(
                'review' => 'review 1',
                'rating' => 4,
            ),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'user',
                'invoice',


            ]
        );

        $this->actingAs($client, 'api');
        $response = $this->json(
            'POST',
            '/api/invoices/sendreview/'.$invoice->last_id,
            array(
                'review' => 'review 1',
                'rating' => 4,
            ),
            [
                'Authorization' => 'Bearer '.$user->api_token,
            ]
        );

        $response->assertJsonStructure(
            [
                'type',
                'user',
                'invoice',


            ]
        );


    }
}
