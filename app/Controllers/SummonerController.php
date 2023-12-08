<?php

namespace App\Controllers;

use App\Controllers;
use App\ErrorHandler;
use App\Exceptions\SummonerNotFoundException;
use App\Models\Database;
use Exception;
use PDOException;
use Slim\Views\PhpRenderer;
use App\Models\Summoner;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SummonerController extends Controller {

    public function render(Request $request, Response $response, array $args) {
        try {
            new Summoner($args['name']);
        } catch (SummonerNotFoundException $e) {
            return $response->withStatus(301)->withHeader('Location', '/404');
        }
        session_start();
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
            return ErrorHandler::sendError($response, 400, $e->getMessage());
        } catch (Exception $e) {
            return ErrorHandler::sendError($response, 500, $e->getMessage());
        }
    }

    public function registerSummoner(Request $request, Response $response, array $args) {
        $summonerName = $args['summonerName'];
        try {
            $summoner = new Summoner($summonerName);
            $database = new Database();
            $conn = $database->getConnection();
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
            return ErrorHandler::sendError($response, 400, $e->getMessage());
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 401, "Ce summoner existe déjà !");
        }
    }

    public function search(Request $request, Response $response, array $args) {
        $summonerName = $args['summonerName'] . "%";
        try {
            $database = new Database();
            $conn = $database->getConnection();
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