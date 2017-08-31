<?php

namespace App\Http\Controllers;

use App\Services\RobotsText;
use App\Services\Sitemap;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    public function __construct()
    {
        //
    }

    public function testSitemap(Request $request, Client $client)
    {
        $test = new Sitemap($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent([ 'test' => 'sitemap', 'results' => $results ]);

        return $response;
    }

    public function testRobotsText(Request $request, Client $client)
    {
        $test = new RobotsText($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent([ 'test' => 'robotsText', 'results' => $results ]);

        return $response;
    }
}
