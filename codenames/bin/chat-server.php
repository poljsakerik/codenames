<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\GameSockets;

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new GameSockets()
            )
        ),
        8080
    );

    $server->run();