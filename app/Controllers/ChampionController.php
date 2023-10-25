<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\SummonerMatch;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ChampionController {
    
    public function update(Request $request, Response $response, array $args) {
        $match = new SummonerMatch($args["matchId"]);
        $database = new Database();
        try {
            $match->save($database);
            $response->getBody()->write(json_encode(["success" => true]));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
            );
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
            return ($response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
            );
        }
    }

}

?>