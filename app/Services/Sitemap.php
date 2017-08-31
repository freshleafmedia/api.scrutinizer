<?php namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Sitemap
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): \stdClass
    {
        $results = new \stdClass();

        try {
            $res = $this->client->request('GET', $URL . '/sitemap.xml');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $results->exists = ($res->getStatusCode() === 200);

        if ($results->exists === false) {
            $results->compressed = null;
        } else {
            try {
                $res = $this->client->request('GET', $URL . '/sitemap.xml.gz');
            } catch (ClientException $e) {
                $res = $e->getResponse();
            }
            $results->compressed = ($res->getStatusCode() === 200);
        }

        return $results;
    }
}
