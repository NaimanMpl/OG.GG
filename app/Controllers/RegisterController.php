<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterController extends Controller {

    public function render(Request $request, Response $response) {
        session_start();
        if (isset($_SESSION["username"]) || !empty($_SESSION["username"])) {
            return $response->withStatus(301)->withHeader('Location', '/');
        }
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'register.php');
    }

    public function handleAccountCreation(Request $request, Response $response) {
        if (!isset($_GET["username"]) || empty($_GET["username"])) {
            return $response->withStatus(301)->withHeader("Location", "/");
        }
        
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'account-activated.php', ["username"=> $_GET["username"]]);
    }

}
?>