<?php

namespace App\Controllers;

use App\ErrorHandler;
use App\Exceptions\DatabaseException;
use App\Models\Database;
use App\Models\SummonerMatch;
use App\Managers\MatchManager;
use Exception;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ChampionController {
    
    public function update(Request $request, Response $response, array $args) {
        $match = new SummonerMatch($args["matchId"]);
        try {
            $database = new Database();
            $matchManager = new MatchManager($database);
            $matchManager->save($match);
            $response->getBody()->write(json_encode(["success" => true]));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
            );
        } catch (Exception $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 401, "Ce match a déjà été enregistre !");
        }
    }

    private function handleChampionLeaderboardUpdate(Database $database, int $championId, string $championName, int $wins) {
        $currentWinsQuery = "SELECT total_wins FROM champions_leaderboard WHERE champion_id=?";
        $conn = $database->getConnection();
        $stmt = $conn->prepare($currentWinsQuery);
        $stmt->execute(array($championId));
        
        if ($stmt->rowCount() == 0) {
            $this->registerChampionOnLeaderboard($database, $championId, $championName, $wins);
            return;
        }

        $query = "UPDATE champions_leaderboard SET total_wins=total_wins+? WHERE champion_id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($wins, $championId));
    }

    private function registerChampionOnLeaderboard(Database $database, int $championId, string $championName, int $wins) {
        $query = "INSERT INTO champions_leaderboard(champion_id, champion_name, total_wins) VALUES (?, ?, ?)";
        $conn = $database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array($championId, $championName, $wins));
    }

    public function updateLeaderboard(Request $request, Response $response) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT DISTINCT(champion_id), champion_name FROM champions_matchs";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $championData = $stmt->fetchAll();
            foreach ($championData as $champion) {
                $winsQuery = "SELECT SUM(win) FROM champions_matchs WHERE champion_id=".$champion["champion_id"];
                $stmt = $conn->prepare($winsQuery);
                $wins = $stmt->execute();

                $this->handleChampionLeaderboardUpdate($database, $champion["champion_id"], $champion["champion_name"], $wins);
            }
            
            $response->getBody()->write(json_encode(["success" => true]));
            return (
                $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
            );
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 402, "Un duplicata a été repéré dans le leaderboard !");
        }
    }
}

?>