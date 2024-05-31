<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use App\controllers\Controller;
use App\controllers\GroupController;
use App\controllers\UserController;

$app->addBodyParsingMiddleware();

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Expose-Headers', 'Content-Disposition');
});

$app->addRoutingMiddleware();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->group('/', function (RouteCollectorProxy $group) {
    $group->get('', Controller::class . ':index');
    $group->get('users', UserController::class . ':getUsers');
    $group->get('users/{id}', UserController::class . ':getUser');
    $group->get('users/groups/{id}', UserController::class . ':getAllUsersByGroup');
    $group->get('groups', GroupController::class . ':getGroups');
    $group->get('groups/{id}', GroupController::class . ':getGroup');
    $group->get('groups/users/{id}', GroupController::class . ':getUsersOfGroup');
});
