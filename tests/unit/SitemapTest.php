<?php

use App\Services\Sitemap;
use Codeception\Util\Stub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class SitemapTest extends \Codeception\Test\Unit
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

    public function testSitemapIsFoundIfStatusCodeIsHttpOk()
    {
        $responseMethods = [
            'getStatusCode' => 200,
            'getResponse' => 'sitemap content'
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertTrue($results->exists);
    }

    public function testSitemapIsNotFoundIfStatusCodeIsHttpNotFound()
    {
        $responseMethods = [
            'getStatusCode' => 404,
            'getResponse' => ''
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertFalse($results->exists);
    }
}