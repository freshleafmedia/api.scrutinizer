<?php namespace App\Services\Appearance;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;

class Favicon implements TestInterface
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


        $faviconLinks = $xpath->query('//head/link[@rel="icon"]');

        if ($faviconLinks->length === 0) {
            $faviconLinks = $xpath->query('//head/link[@rel="shortcut icon"]');
        }

        if ($faviconLinks->length === 0) {
            $results->addProblem('Favicon link tag is missing');
            return $results;
        }

        if ($faviconLinks->length > 1) {
            $results->addProblem('Multiple favicon link tags');
            return $results;
        }

        $favicon = $faviconLinks[0];

        $faviconHref = $favicon->getAttribute('href');

        if (strpos($faviconHref, 'http') !== 0) {
            $faviconHref = $URL . $faviconHref;
        }

        $results->addData('url', $faviconHref);

        try {
            $res = $this->client->request('GET', $faviconHref);
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        if ($res->getStatusCode() === 404) {
            $results->addProblem('Favicon URL returns a 404');
            return $results;
        }

        return $results;
    }
}
