<?php namespace App\Http\Controllers;

use App\Services\Security\SslLabs;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecurityController extends Controller
{
    public function listAll()
    {
        $tests = [
            'SSL Labs',
        ];

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($tests);

        return $response;
    }

    public function testSslLabs(Request $request, Client $client)
    {
        $test = new SslLabs($client);

        $results = $test->run($request->get('url'));

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($results->getAllMessages());

        return $response;
    }
}
