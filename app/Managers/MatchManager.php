<?php

namespace App\Managers;

use App\Models\Database;
use App\Models\SummonerMatch;
use App\Exceptions\DatabaseException;
use Exception;
use PDOException;

class MatchManager {

    private Database $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function save(SummonerMatch $match) {
        try {
            $con = $this->database->getConnection();
            foreach ($match->getParticipants() as $participant) {
                $win = $participant["win"] ? 1 : 0;
                $query = "INSERT INTO champions_matchs(summoner_id, summoner_puuid, champion_id, champion_name, match_id, win) VALUES (?, ?, ?, ?, ?, ?)";
                try {
                    $stmt = $con->prepare($query);
                    $stmt->execute(array(
                        $participant["summonerId"],
                        $participant["puuid"],
                        $participant["championId"], 
                        $participant["championName"], 
                        $match->getMatchId(),
                        $win
                    ));
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    continue;
                }
            }
        } catch (PDOException $e) {
            throw new DatabaseException($e->errorInfo[1], "La connexion à la base de donnée échouée. ".$e->getMessage());
        }
    }

}

?>