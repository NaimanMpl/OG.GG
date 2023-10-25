<?php

namespace App\Models;

use App\Models\Database;
use Exception;

class SummonerMatch {

    private string $matchId;
    private array $participants;

    public function __construct(string $matchId) {
        $this->matchId = $matchId;
        $this->participants = [];
        $matchUrl = "https://europe.api.riotgames.com/lol/match/v5/matches/".$matchId."?api_key=".$_ENV["RIOT_API_KEY"];
        $matchData = json_decode(file_get_contents($matchUrl), true);
        $summoners = $matchData["info"]["participants"];
        foreach ($summoners as $summoner) {
            $this->participants[] = [
                "championName" => $summoner["championName"],
                "championId" => $summoner["championId"],
                "win" => $summoner["win"]
            ];
        }

    }

    public function save(Database $database) {
        try {
            $con = $database->connect();
            foreach ($this->participants as $participant) {
                $win = $participant["win"] ? 1 : 0;
                $query = "INSERT INTO champions_matchs(champion_id, champion_name, match_id, win) VALUES (?, ?, ?, ?)";
                $stmt = $con->prepare($query);
                $stmt->execute(array(
                    $participant["championId"], 
                    $participant["championName"], 
                    $this->matchId,
                    $win,
                ));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            throw new Exception("La connexion à la base de donnée échouée.");
        }
    }

}

?>