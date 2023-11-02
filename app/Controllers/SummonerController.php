<?php

namespace App\Controllers;

use App\Controllers;
use App\Exceptions\SummonerNotFoundException;
use App\Models\Database;
use PDOException;
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

    public function registerSummoner(Request $request, Response $response, array $args) {
        $summonerName = $args['summonerName'];
        try {
            $summoner = new Summoner($summonerName);
            $database = new Database();
            $conn = $database->connect();
            $query = "INSERT INTO summoners(id, puuid, name, level, profileiconid) VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($summoner->getId(), $summoner->getPuuid(), $summoner->getName(), $summoner->getLevel(), $summoner->getProfileIconId()));
            $response->getBody()->write(json_encode(['success' => true]));
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
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $response->getBody()->write(json_encode(["error" => "Ce summoner existe déjà !"]));
                return (
                    $response
                        ->withStatus(401)
                        ->withHeader('Content-Type', 'application/json')
                );
            } else {
                $response->getBody()->write(json_encode(["error" => "Impossible d'enregistrer le summoner, côté base de données."]));
                return (
                    $response
                        ->withStatus(500)
                        ->withHeader('Content-Type', 'application/json')
                );
            }
        }
    }

    public function search(Request $request, Response $response, array $args) {
        $summonerName = $args['summonerName'] . "%";
        try {
            $database = new Database();
            $conn = $database->connect();
            $query = "SELECT name, profileIconId FROM summoners WHERE name LIKE ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($summonerName));
            $summoners = $stmt->fetchAll();
            $response->getBody()->write(json_encode(["results" => $summoners]));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
            );
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(["error"=> $e->getMessage()]));
            return (
                $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", "application/json")
            );
        }
    }
}

?>