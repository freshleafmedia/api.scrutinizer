<?php namespace App\Services\Security;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SslLabs implements TestInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): TestResult
    {
        $results = new TestResult();

        for ($i = 0; $i < 25; $i++) {
            $res = $this->client->request('GET', 'https://api.ssllabs.com/api/v2/analyze?host=' . urlencode($URL));

            $checkStatus = json_decode($res->getBody()->getContents());

            if ($checkStatus->status !== 'READY') {
                sleep(5);
                continue;
            }

            $grade = $checkStatus->endpoints[0]->grade;

            switch ($grade) {
                case 'A+':
                case 'A':
                case 'B':
                case 'C':
                    break;
                case 'D':
                case 'E':
                case 'F':
                    $results->addProblem('Poor TLS configuration');
                    break;
            }

            $results->addData('grade', $grade);
            break;
        }

        return $results;
    }
}
