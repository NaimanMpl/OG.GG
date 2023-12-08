<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class Controller {

    public abstract function render(Request $request, Response $response, array $args);

}

?>