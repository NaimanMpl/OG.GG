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
        $HTTP_STATUS_CODE = 301;
        $db = new Database();
        $con = $db->getConnection();
        $userInput = json_decode($request->getBody(), true);

        if (!isset($userInput["email"]) || !isset($userInput["password"]) || empty($userInput["email"]) || empty($userInput["password"])) {
            $HTTP_STATUS_CODE = 400;
            $response->getBody()->write(json_encode(["error" => "Identifiant ou mot de passe incorrect."]));
            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array($userInput['email']));
        $user = $stmt->fetch();

        if (!$user || !password_verify($userInput["password"], $user["password"])) {
            $HTTP_STATUS_CODE = 401;
            $response->getBody()->write(json_encode(["error" => "Identifiant ou mot de passe incorrect."]));
            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        $response->getBody()->write(json_encode(["success" => true]));
        session_start();
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        return ($response
            ->withStatus($HTTP_STATUS_CODE)
            ->withHeader('Location', '/')
        );
    }

}

?>