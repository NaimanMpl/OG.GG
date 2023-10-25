<?php

namespace App\Controllers;

use App\Controllers;
use App\Exceptions\SummonerNotFoundException;
use Slim\Views\PhpRenderer;
use App\Models\Summoner;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SummonerController extends Controller {

    public function render(Request $request, Response $response) {
        $renderer = new PhpRenderer("../views");
        return $renderer->render($response->withStatus(200), "summoner-page.php");
    }

    public function getSummoner(Request $request, Response $response, array $args) {
        try {
            $summoner = new Summoner($args['summonerName']);
            $response->getBody()->write(json_encode($summoner->toArray()));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
            );
        } catch (SummonerNotFoundException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return (
                $response
                    ->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
            );
        }
    }

}

?>