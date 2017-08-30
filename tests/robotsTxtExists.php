<?php namespace ScrutinizerApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RobotsTxtExistsTest
{
    public function run($URL): boolean
    {
        $client = new Client();

        try {
            $res = $client->request('GET', $URL . '/robots.txt');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        return ($res->getStatusCode() !== 404);
    }
}
