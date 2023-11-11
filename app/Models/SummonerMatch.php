<?php

namespace App\Models;

use App\Exceptions\DatabaseException;
use App\Models\Database;
use PDOException;

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
                "summonerId" => $summoner["summonerId"],
                "puuid" => $summoner["puuid"],
                "championName" => $summoner["championName"],
                "championId" => $summoner["championId"],
                "win" => $summoner["win"]
            ];
        }

    }

    public function getMatchId(): string {
        return $this->matchId;
    }

    public function getParticipants(): array {
        return $this->participants;
    }

}

?>