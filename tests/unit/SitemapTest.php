<?php

use App\Services\SEO\Sitemap;
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

    public function testExistsIsTrueIfSitemapIsFound()
    {
        $responseMethods = [
            'getStatusCode' => 200,
            'getResponse' => 'sitemap content'
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 0);
    }

    public function testExistsIsFalseIfSitemapIsNotFound()
    {
        $responseMethods = [
            'getStatusCode' => 404,
            'getResponse' => ''
        ];

        $responseClass = Stub::make(Response::class, $responseMethods);
        $client = Stub::make(Client::class, ['request' => $responseClass ]);

        $test = new Sitemap($client);

        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 1);
    }

    public function testCompressedIsTrueIfCompressedSitemapIsFound()
    {
        $requestFunc = function ($method, $URL) {
            if ($URL === 'http://example.org/sitemap.xml') {
                $responseMethods = [
                    'getStatusCode' => 200,
                    'getResponse' => ''
                ];
            }

            if ($URL === 'http://example.org/sitemap.xml.gz') {
                $responseMethods = [
                    'getStatusCode' => 200,
                    'getResponse' => ''
                ];
            }

            return Stub::make(Response::class, $responseMethods);
        };

        $client = Stub::make(Client::class, ['request' => $requestFunc ]);

        $test = new Sitemap($client);
        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getProblems()) === 0);
    }

    public function testCompressedIsFalseIfCompressedSitemapIsNotFound()
    {
        $requestFunc = function ($method, $URL) {
            if ($URL === 'http://example.org/sitemap.xml') {
                $responseMethods = [
                    'getStatusCode' => 200,
                    'getResponse' => ''
                ];
            }

            if ($URL === 'http://example.org/sitemap.xml.gz') {
                $responseMethods = [
                    'getStatusCode' => 404,
                    'getResponse' => ''
                ];
            }

            return Stub::make(Response::class, $responseMethods);
        };

        $client = Stub::make(Client::class, ['request' => $requestFunc ]);

        $test = new Sitemap($client);
        $results = $test->run('http://example.org');

        $this->assertTrue(count($results->getWarnings()) === 1);
    }
}