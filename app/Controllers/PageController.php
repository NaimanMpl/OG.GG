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

    public function renderChat(Request $request, Response $response) {
        return $this->renderer->render($response->withStatus(200), 'chat.php');
    }

}

?>