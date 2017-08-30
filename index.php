<?php namespace ScrutinizerApi;

use GuzzleHttp\Client;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ScrutinizerApi\Tests\RobotsTxtExistsTest;

// Make sure composer dependencies have been installed
require __DIR__ . '/vendor/autoload.php';

class Api implements MessageComponentInterface
{
    public function __construct()
    {
    }

    public function onOpen(ConnectionInterface $conn)
    {
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);

        if ($data->request === '/meta') {
            $client = new Client();

            try {
                $res = $client->request('GET', $data->body->URL);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $res = $e->getResponse();
            }

            $metaData = new \stdClass();
            $metaData->response = $res->getStatusCode();

            $from->send(json_encode($metaData));
        }

        if ($data->request === '/tests/robotsTextExists') {
            $test = new RobotsTxtExistsTest();

            if ($test->run($data->body->URL)) {
                $from->send('{"pass": "true" }');
            } else {
                $from->send('{"pass": "false" }');
            }
        }

        if ($data->request === '/tests/xmlSitemapExists') {
            $client = new Client();

            try {
                $res = $client->request('GET', $data->body->URL . '/sitemap.xml');
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $res = $e->getResponse();
            }

            if ($res->getStatusCode() === 404) {
                $from->send('{"pass": "false" }');
            } else {
                $from->send('{"pass": "true" }');
            }
        }

        if ($data->request === '/tests/compressedXmlSitemapExists') {
            $client = new Client();

            try {
                $res = $client->request('GET', $data->body->URL . '/sitemap.xml.gz');
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $res = $e->getResponse();
            }

            if ($res->getStatusCode() === 404) {
                $from->send('{"pass": "false" }');
            } else {
                $from->send('{"pass": "true" }');
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e;
        $conn->close();
    }
}

// Run the server application through the WebSocket protocol on port 8080
$app = new Ratchet\App('localhost', 8080);
$app->route('/api', new Api, array('*'));
$app->run();
