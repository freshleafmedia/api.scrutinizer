<?php

use GuzzleHttp\Client;
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

        $from->send($data->request);

        if ($data->request === 'meta') {
            $client = new Client();
            $from->send($data->body->URL);

            try {
                $res = $client->request('GET', $data->body->URL);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $res = $e->getResponse();
            }

            $metaData = new \stdClass();
            $metaData->response = $res->getStatusCode();

            $from->send(json_encode($metaData));
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
