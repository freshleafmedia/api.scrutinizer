<?php namespace App\Services\Appearance;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;

class ViewportMeta implements TestInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run(string $URL): TestResult
    {
        $results = new TestResult();

        $request = $this->client->request('GET', $URL);

        $dom = new DOMDocument;
        libxml_use_internal_errors(true); // sadface
        $dom->loadHTML($request->getBody());
        $xpath = new DOMXPath($dom);

        $viewportMetaTags = $xpath->query('//head/meta[@name="viewport"]');

        if ($viewportMetaTags->length === 0) {
            $results->addProblem('viewport meta tag is missing');
            return $results;
        }

        if ($viewportMetaTags->length > 1) {
            $results->addProblem('Multiple viewport meta tags');
            return $results;
        }

        $viewportMetaTag = $viewportMetaTags[0];

        // TODO: need to test for initial-scale using the decimal 1.0
        // TODO: Need to test for the content in any order
        if ($viewportMetaTag->getAttribute('content') !== 'width=device-width, initial-scale=1') {
            $results->addProblem('Viewport meta tags content is incorrect');
            return $results;
        }

        return $results;
    }
}
