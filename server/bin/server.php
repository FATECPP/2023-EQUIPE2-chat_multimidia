<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer; 
use App\Chat;
use Dotenv;
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    $_ENV['WEB_SOCKETS_PORT']
);

echo "Server started on port ". $_ENV['WEB_SOCKETS_PORT']. "\n";
$server->run();
