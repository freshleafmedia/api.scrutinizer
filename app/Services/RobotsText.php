<?php namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RobotsText
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): \stdClass
    {
        try {
            $res = $this->client->request('GET', $URL . '/robots.txt');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $results = new \stdClass();
        $results->exists = ($res->getStatusCode() === 200);

        if ($results->exists === false) {
            $results->empty = null;
        } else {
            $results->empty = ($res->getBody() === '');
        }

        return $results;
    }
}
