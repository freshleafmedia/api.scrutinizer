<?php namespace FreshleafMedia\ScrutinizerApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class XmlSitemapExists
{
    public function getName(): string
    {
        return 'xmlSitemapExists';
    }

    public function run($URL): bool
    {
        $client = new Client();

        try {
            $res = $client->request('GET', $URL . '/sitemap.xml');
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        return ($res->getStatusCode() !== 404);
    }
}
