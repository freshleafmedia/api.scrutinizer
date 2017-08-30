<?php namespace FreshleafMedia\ScrutinizerApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RobotsTxtExistsTest
{
    public function getName(): string
    {
        return 'robotsTxtExistsTest';
    }

    public function run($URL): bool
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
