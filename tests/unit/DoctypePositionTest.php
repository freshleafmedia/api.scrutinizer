<?php

use App\Services\Technical\DoctypePosition;
use Codeception\Util\Stub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class DoctypePositionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testTheDoctypeMustBeFirstContentInBody()
    {
        $responseMethods = [
            'getBody' => '<!DOCTYPE html>',
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new DoctypePosition($client);

        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 0);
    }
}
