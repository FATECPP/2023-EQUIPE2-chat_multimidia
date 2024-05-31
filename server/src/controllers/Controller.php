<?php

namespace App\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use MongoDB;
use Dotenv;

class Controller
{
    public function index(Request $request, Response $response){
        $response->getBody()->write('<h1>Hello World</h1>');
        return $response;
    }
    protected function getDatabase()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $mongoConnectionString = sprintf(
            'mongodb://%s:%s@%s:%s',
            $_ENV['MONGODB_USERNAME'],
            $_ENV['MONGODB_PASSWORD'],
            $_ENV['MONGODB_HOST'],
            $_ENV['MONGODB_PORT']
        );        
        return new MongoDB\Client($mongoConnectionString);
    }
}