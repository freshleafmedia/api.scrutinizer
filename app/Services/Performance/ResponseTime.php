<?php namespace App\Services\Performance;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;

class ResponseTime implements TestInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): TestResult
    {
        $results = new TestResult();

        $this->client->request('GET', $URL, [
            'on_stats' => function (TransferStats $stats) use ($results) {

                if ($stats->getTransferTime() > 1) {
                    $results->addProblem('Server slow to respond ('.$stats->getTransferTime().')');
                } elseif ($stats->getTransferTime() > 0.5) {
                    $results->addWarning('Server slow to respond ('.$stats->getTransferTime().')');
                }
            }
        ]);

        return $results;
    }
}
