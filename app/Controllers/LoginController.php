<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Controllers\Controller;

class LoginController extends Controller {

    public function render(Request $request, Response $response, array $args) {
        session_start();
        if (isset($_SESSION["username"]) || !empty($_SESSION["username"])) {
            return $response->withStatus(301)->withHeader('Location', '/');
        }
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'login.php');
    }

}

?>