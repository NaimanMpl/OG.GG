<?php

namespace App\Controllers;

use App\Models\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {

    public function getUserByEmail(Request $request, Response $response) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT email FROM users WHERE email=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(strtolower(urldecode($request->getQueryParams()['email']))));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["email" => $stmt->fetch()["email"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function getUserByName(Request $request, Response $response, array $args) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT username FROM users WHERE username=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(urldecode($args['username'])));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["username" => $stmt->fetch()["username"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function logout(Request $request, Response $response) {
        session_start();
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        $_SESSION = array();
        return $response->withStatus(301)->withHeader('Location', '/');
    }
}

?>