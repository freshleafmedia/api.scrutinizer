<?php namespace App\Services;

use App\Contracts\TestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RobotsText implements TestInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(\string $URL): TestResult
    {
        try {
            $res = $this->client->request('GET', $URL . '/robots.txt');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $results = new TestResult();

        if ($res->getStatusCode() === 404) {
            $results->addProblem('Robots.txt was not found');
        }

        if ($res->getStatusCode() === 200 && $res->getBody() === '') {
            $results->addWarning('Robots.txt appears to be empty');
        }

        return $results;
    }
}
