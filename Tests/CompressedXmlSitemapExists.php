<?php namespace FreshleafMedia\ScrutinizerApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CompressedXmlSitemapExists
{
    public function getName()
    {
        return 'compressedXmlSitemapExists';
    }

    public function run($URL): bool
    {
        $client = new Client();

        try {
            $res = $client->request('GET', $URL . '/sitemap.xml.gz');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        return ($res->getStatusCode() !== 404);
    }
}
