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
        $responseData = [
            'getStatusCode' => 200,
            'getResponse' => 'sitemap content'
        ];

        $request = Stub::make(Response::class, $responseData);
        $client = Stub::make(Client::class, ['request' => $request ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertTrue($results->exists);
    }

    public function testSitemapIsNotFoundIfStatusCodeIsHttpNotFound()
    {
        $responseData = [
            'getStatusCode' => 404,
            'getResponse' => ''
        ];

        $request = Stub::make(Response::class, $responseData);
        $client = Stub::make(Client::class, ['request' => $request ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertFalse($results->exists);
    }
}