<?php namespace App\Services\Technical;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;

class DoctypePosition implements TestInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): TestResult
    {
        $results = new TestResult();

        try {
            $res = $this->client->request('GET', $URL);
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }


        if (strtolower(substr($res->getBody(), 0, 9)) !== '<!doctype') {
            $results->addProblem('Doctype declaration should be the first thing in the body');
        }

        return $results;
    }
}
