<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\SummonerMatch;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\MatchNotFoundException;
use App\Models\Database;
use Exception;
use App\Managers\MatchManager;
use App\ErrorHandler;

class MatchController extends Controller {

    public function render(Request $request, Response $response, array $args) {
        return $response->withStatus(200);
    }

    public function getMatch(Request $request, Response $response, array $args) {
        $matchId = $args["matchId"];
        try {
            $summonerMatch = new SummonerMatch($matchId);
            $database = new Database();
            $matchManager = new MatchManager($database);
            $matchManager->save($summonerMatch);
            $response->getBody()->write(json_encode($summonerMatch->toArray()));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
            );
        } catch (MatchNotFoundException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return (
                $response
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json')
            );
        }
    }
}



?>