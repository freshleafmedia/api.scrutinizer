<?php namespace FreshleafMedia\ScrutinizerApi;

use FreshleafMedia\ScrutinizerApi\Tests\CompressedXmlSitemapExists;
use FreshleafMedia\ScrutinizerApi\Tests\RobotsTxtExistsTest;
use FreshleafMedia\ScrutinizerApi\Tests\XmlSitemapExists;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ratchet\App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

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
            } catch (ClientException $e) {
                $res = $e->getResponse();
            }

            $metaData = new \stdClass();
            $metaData->response = $res->getStatusCode();

            $from->send(json_encode($metaData));
        }

        if ($data->request === '/tests/all') {
            $tests = [
                RobotsTxtExistsTest::class,
                XmlSitemapExists::class,
                CompressedXmlSitemapExists::class,
            ];

            foreach ($tests as $test) {
                $result = $this->runTest($test, $data->body->URL);
                $from->send(json_encode($result));
            }

        }

        if ($data->request === '/tests/robotsTextExists') {
            $result = $this->runTest(RobotsTxtExistsTest::class, $data->body->URL);
            $from->send(json_encode($result));
        }

        if ($data->request === '/tests/xmlSitemapExists') {
            $result = $this->runTest(XmlSitemapExists::class, $data->body->URL);
            $from->send(json_encode($result));
        }

        if ($data->request === '/tests/compressedXmlSitemapExists') {
            $result = $this->runTest(CompressedXmlSitemapExists::class, $data->body->URL);
            $from->send(json_encode($result));
        }
    }

    protected function runTest($test, $url): \stdClass
    {
        $test = new $test;

        $result = new \stdClass();
        $result->pass = $test->run($url);
        $result->testName = $test->getName();

        return $result;
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
$app = new App('localhost', 8080);
$app->route('/api', new Api, array('*'));
$app->run();
