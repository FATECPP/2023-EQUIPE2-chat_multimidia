<?php

namespace App\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\DAO\UserDAO;
use App\models\UserModel;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(new UserDAO($this->getDataBase()));
    }

    public function getUsers(Request $request, Response $response)
    {
        $response->getBody()->write(json_encode($this->userModel->getAllUsers()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getUser(Request $request, Response $response, $args){
        $response->getBody()->write(json_encode($this->userModel->getUserById($args['id'])));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAllUsersByGroup(Request $request, Response $response, $args){
        $response->getBody()->write(json_encode($this->userModel->getAllUsersByGroupId($args['id'])));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
