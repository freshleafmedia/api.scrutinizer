<?php namespace App\Services\SEO;

use App\Contracts\TestInterface;
use App\Services\TestResult;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class TitleTag implements TestInterface
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

        $titleTags = $xpath->query('//head/title');

        if ($titleTags->length === 0) {
            $results->addProblem('Title tag is missing');
            return $results;
        }

        if ($titleTags->length > 1) {
            $results->addProblem('Multiple title tags');
            return $results;
        }

        $titleTag = $titleTags[0];
        $titleTagContent = trim($titleTag->textContent);

        if (empty($titleTagContent)) {
            $results->addProblem('Title tag is empty');
            return $results;
        }

        if (strlen($titleTagContent) > 60) {
            $results->addWarning('Title tag is too long');
            $results->addData('titleLength', strlen($titleTagContent));
            return $results;
        }

        return $results;
    }
}
