<?php namespace App\Http\Controllers;

use App\Services\Performance\ResponseTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PerformanceController extends Controller
{
    public function listAll()
    {
        $tests = [
            'Request Time',
        ];

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($tests);

        return $response;
    }

    public function testResponseTime(Request $request, Client $client)
    {
        $test = new ResponseTime($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }
}
