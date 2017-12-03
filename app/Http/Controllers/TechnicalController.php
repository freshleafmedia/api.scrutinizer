<?php namespace App\Http\Controllers;

use App\Services\Security\SslLabs;
use App\Services\Technical\DoctypePosition;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TechnicalController extends Controller
{
    public function listAll()
    {
        $tests = [
            'Doctype Position',
        ];

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($tests);

        return $response;
    }

    public function testDoctypePosition(Request $request, Client $client)
    {
        $test = new DoctypePosition($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }
}
