<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    public function __construct()
    {
        //
    }

    public function testXmlSitemapExists(Request $request, Client $client)
    {
        try {
            $res = $client->request('GET', $request->get('url') . '/sitemap.xml');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        $response->setContent([ 'pass' => ($res->getStatusCode() === 200) ]);
        return $response;
    }

    public function testCompressedXmlSitemapExists(Request $request, Client $client)
    {
        try {
            $res = $client->request('GET', $request->get('url') . '/sitemap.xml.gz');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        $response->setContent([ 'pass' => ($res->getStatusCode() === 200) ]);
        return $response;
    }

    public function testRobotsTextExists(Request $request, Client $client)
    {
        try {
            $res = $client->request('GET', $request->get('url') . '/robots.txt');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        $response->setContent([ 'pass' => ($res->getStatusCode() === 200) ]);
        return $response;
    }
}
