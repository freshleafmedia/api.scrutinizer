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

    public function testForFailureIfDoctypeIsMissing()
    {
        $responseMethods = [
            'getBody' => '<html><head>',
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new DoctypePosition($client);

        $results = $test->run('http://example.org');

        $this->assertFalse(count($results->getProblems()) === 0);
    }

    public function testForFailureWhenDoctypeIsPrecededWithWhitespace()
    {
        $responseMethods = [
            'getBody' => '    <!DOCTYPE html>',
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new DoctypePosition($client);

        $results = $test->run('http://example.org');

        $this->assertFalse(count($results->getProblems()) === 0);
    }

    public function testTheDoctypeChecksAreCaseInsensitive()
    {
        $responseMethods = [
            'getBody' => '<!doctype html>',
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new DoctypePosition($client);

        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 0);
    }

    public function testThatOldDoctypesAreAccepted()
    {
        $responseMethods = [
            'getBody' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new DoctypePosition($client);

        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 0);
    }
}
