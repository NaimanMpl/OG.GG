<?php

namespace App\Controllers;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageController {

    private PhpRenderer $renderer;

    public function __construct() {
        $this->renderer = new PhpRenderer('../views');
    }

    public function renderChat(Request $request, Response $response, array $args) {
        session_start();
        if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
            return $response->withStatus(301)->withHeader('Location', '/login');
        }
        return $this->renderer->render($response->withStatus(200), 'chat.php');
    }

    public function renderFollowers(Request $request, Response $response, array $args) {
        session_start();
        if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
            return $response->withStatus(301)->withHeader('Location', '/login');
        }
        return $this->renderer->render($response->withStatus(200), 'followers.php');
    }

    public function renderSettings(Request $request, Response $response, array $args) {
        session_start();
        if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
            return $response->withStatus(301)->withHeader('Location', '/login');
        }
        return $this->renderer->render($response->withStatus(200), 'settings.php');
    }

}

?>