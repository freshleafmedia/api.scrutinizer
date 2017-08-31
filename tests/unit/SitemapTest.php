<?php

use Codeception\Util\Stub;

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

        $response = Stub::make(\GuzzleHttp\Psr7\Response::class, $responseData);
        $request = Stub::make(GuzzleHttp\Client::class, ['request' => $response ]);

        $test = new \App\Services\Sitemap($request);

        $results = $test->run('http://example.org');

        $this->assertTrue($results->exists);
    }
}