<?php

namespace App\Http\Controllers;

use App\Services\Appearance\Favicon;
use App\Services\Appearance\ViewportMeta;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppearanceController extends Controller
{
    public function listAll()
    {
        $tests = [
            'Favicon',
            'Viewport Meta',
        ];

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($tests);

        return $response;
    }

    public function testFavicon(Request $request, Client $client)
    {
        $test = new Favicon($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }

    public function testViewportMeta(Request $request, Client $client)
    {
        $test = new ViewportMeta($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }
}
