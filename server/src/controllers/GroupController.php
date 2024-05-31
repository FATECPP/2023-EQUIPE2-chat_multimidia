<?php

namespace App\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\DAO\GroupDAO;
use App\models\GroupModel;

class GroupController extends Controller
{
    private $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel(new GroupDAO($this->getDataBase()));
    }

    public function getGroups(Request $request, Response $response){
        $response->getBody()->write(json_encode($this->groupModel->getAllGroups()));
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function getGroup(Request $request, Response $response, $args){
        $response->getBody()->write(json_encode($this->groupModel->getGroupsByIdUser($args['id'])));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getUsersOfGroup(Request $request, Response $response, $args){
      $response->getBody()->write(json_encode($this->groupModel->getUsersOfGroup($args['id'])));
      return $response->withHeader('Content-Type', 'application/json');
    }
}
