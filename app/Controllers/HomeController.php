<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Controller\Controllers;


class HomeController extends Controller {

    public function render(Request $request, Response $response) {
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'index.php');
    }

}
?>