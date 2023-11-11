<?php

namespace App\Models;

use App\Exceptions\DatabaseException;
use App\Exceptions\MatchNotFoundException;
use App\Models\Database;
use PDOException;
use \DateTime;
use \DateInterval;

class SummonerMatch {

    private string $matchId;
    private array $participants;
    private DateTime $matchDuration;
    private DateTime $matchTimestamp;
    private int $diffInSeconds;

    public function __construct(string $matchId) {
        $this->matchId = $matchId;
        $this->participants = [];
        $this->matchDuration = new DateTime('@0');
        $this->matchTimestamp = new DateTime();
        $currentDate = new DateTime();

        $matchUrl = "https://europe.api.riotgames.com/lol/match/v5/matches/".$matchId."?api_key=".$_ENV["RIOT_API_KEY"];

        $matchResponse = file_get_contents($matchUrl);
        if ($matchResponse === false) {
            throw new MatchNotFoundException('Le match '.urldecode($matchId)." n'a pas pu être chargé ou n'existe pas.");
        }

        $matchData = json_decode($matchResponse, true);

        $summoners = $matchData["info"]["participants"];
        $this->matchDuration->add(new DateInterval('PT' . $matchData["info"]["gameDuration"] . 'S'));
        $this->matchDuration->format('i:s');
        $this->matchTimestamp->setTimestamp($matchData["info"]["gameStartTimestamp"]);
        $this->matchTimestamp->format("Y-m-d H:i:s");
        $diff = $currentDate->diff($this->matchTimestamp);
        $this->diffInSeconds = $diff->s + ($diff->i * 60) + ($diff->h * 3600) + ($diff->d * 86400);

        foreach ($summoners as $summoner) {
            $this->participants[] = [
                "summonerName" => $summoner["summonerName"],
                "summonerId" => $summoner["summonerId"],
                "puuid" => $summoner["puuid"],
                "championName" => $summoner["championName"],
                "championId" => $summoner["championId"],
                "championLevel" => $summoner["champLevel"],
                "win" => $summoner["win"],
                "role" => $summoner["teamPosition"],
                "kda" => $summoner["challenges"]["kda"],
                "kills" => $summoner["kills"],
                "deaths" => $summoner["deaths"],
                "assists" => $summoner["assists"],
                "totalCs" => $summoner["totalMinionsKilled"] + $summoner["neutralMinionsKilled"]
            ];
        }

    }

    public function toArray(): array {
        return [
            'matchId' => $this->matchId,
            'matchDuration' => [
                "minutes" => $this->matchDuration->format('i'),
                "seconds" => $this->matchDuration->format('s'),
            ],
            'matchHappened' => $this->diffInSeconds,
            'participants' => $this->participants
        ];
    }

    public function getMatchId(): string {
        return $this->matchId;
    }

    public function getParticipants(): array {
        return $this->participants;
    }

}

?>