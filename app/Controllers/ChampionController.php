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
use Slim\Views\PhpRenderer;

class ChampionController extends Controller {

    public function render(Request $request, Response $response) {
        session_start();
        $renderer = new PhpRenderer("../views");
        return $renderer->render($response, "leaderboard.php");
    }
    
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

    private function handleChampionLeaderboardUpdate(Database $database, int $championId, string $championName, int $wins, int $looses) {
        $currentWinsQuery = "SELECT total_wins FROM champions_leaderboard WHERE champion_id=?";
        $conn = $database->getConnection();
        $stmt = $conn->prepare($currentWinsQuery);
        $stmt->execute(array($championId));
        
        if ($stmt->rowCount() == 0) {
            $this->registerChampionOnLeaderboard($database, $championId, $championName, $wins, $looses);
            return;
        }

        $query = "UPDATE champions_leaderboard SET total_wins=?, total_looses=? WHERE champion_id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($wins, $looses, $championId));
    }

    private function registerChampionOnLeaderboard(Database $database, int $championId, string $championName, int $wins, int $looses) {
        $query = "INSERT INTO champions_leaderboard(champion_id, champion_name, total_wins, total_looses) VALUES (?, ?, ?, ?)";
        $conn = $database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array($championId, $championName, $wins, $looses));
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
                $loosesQuery = "SELECT COUNT(champion_id) FROM champions_matchs WHERE champion_id=".$champion["champion_id"]." AND win=0";
                $stmt = $conn->prepare($winsQuery);
                $stmt->execute();
                $wins = $stmt->fetch()[0];

                $stmt = $conn->prepare($loosesQuery);
                $stmt->execute();
                $looses = $stmt->fetch()[0];

                $this->handleChampionLeaderboardUpdate($database, $champion["champion_id"], $champion["champion_name"], $wins, $looses);
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

    public function getLeaderboard(Request $request, Response $response) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT champion_name, total_wins, total_looses, ROW_NUMBER() OVER(ORDER BY total_wins DESC) AS `rank` FROM champions_leaderboard LIMIT 0, 10";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $leaderboardData = $stmt->fetchAll();
            $leaderboard = array();
            foreach ($leaderboardData as $championData) {
                $leaderboard[] = array(
                    "championName" => $championData["champion_name"],
                    "wins" => $championData["total_wins"],
                    "looses" => $championData["total_looses"],
                    "rank" => $championData["rank"],
                );
            }
            $response->getBody()->write(json_encode($leaderboard));
            return $response->withStatus(200)->withHeader("Content-Type", 'application/json');
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 500, "");
        }
    }
}

?>