<?php

namespace App\Http\Controllers;

use App\Services\SEO\RobotsText;
use App\Services\SEO\Sitemap;
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
        $response->setContent($results->getAllMessages());

        return $response;
    }

    public function testRobotsText(Request $request, Client $client)
    {
        $test = new RobotsText($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }
}
