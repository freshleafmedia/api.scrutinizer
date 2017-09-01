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

    public function run(string $URL): TestResult
    {
        try {
            $res = $this->client->request('GET', $URL . '/sitemap.xml');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $results = new TestResult();

        if ($res->getStatusCode() === 404) {
            $results->addProblem('A sitemap.xml file could not be found');
        }

        if ($res->getStatusCode() === 200) {
            try {
                $res = $this->client->request('GET', $URL . '/sitemap.xml.gz');
            } catch (ClientException $e) {
                $res = $e->getResponse();
            }

            $results->addWarning('A compressed sitemap (sitemap.xml.gz) could not be found');
        }

        return $results;
    }
}
