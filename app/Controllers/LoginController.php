<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Controllers\Controller;

class LoginController extends Controller {

    public function render(Request $request, Response $response) {
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'login.php');
    }

    public function login(Request $request, Response $response) {
        $HTTP_STATUS_CODE = 200;
        $db = new Database();
        $con = $db->connect();
        $userInput = json_decode($request->getBody(), true);
        $query = "SELECT password FROM users WHERE username='".$userInput['username']."'";
        $user = $con->query($query)->fetch();

        if (!isset($userInput["username"]) || !isset($userInput["password"]) || empty($userInput["username"]) || empty($userInput["password"])) {
            $HTTP_STATUS_CODE = 400;
            $response->getBody()->write(json_encode(["error" => "Identifiant ou mot de passe incorrect."]));
            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }
        
        if (!password_verify($userInput["password"], $user["password"])) {
            $HTTP_STATUS_CODE = 401;
            $response->getBody()->write(json_encode(["error" => "Identifiant ou mot de passe incorrect."]));
            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        $response->getBody()->write(json_encode(["success" => true]));
        return ($response
            ->withStatus($HTTP_STATUS_CODE)
            ->withHeader('Content-Type', 'application/json')
        );
    }

}

?>