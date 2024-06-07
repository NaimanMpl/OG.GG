<?php

namespace App\Models;

use App\Exceptions\DatabaseException;
use App\Exceptions\MatchNotFoundException;
use Exception;
use App\Models\Database;
use PDOException;
use \DateTime;
use \DateInterval;

class SummonerMatch {

    private string $matchId;
    private array $participants;
    private array $teams;
    private string $queueId;
    private DateTime $matchDuration;
    private DateTime $matchTimestamp;
    private int $maxDamage;
    private int $maxGolds;
    private int $diffInSeconds;
    private int $winnerTeam;

    public function __construct(string $matchId) {
        $this->matchId = $matchId;
        
        $ch = curl_init();
        $apiUrl = 'https://europe.api.riotgames.com/lol/match/v5/matches/'.urlencode($this->matchId);

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $headers = [
            'X-Riot-Token: '.$_ENV['RIOT_API_KEY']
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        curl_close($ch);

        $this->participants = [];
        $this->teams = [];
        $this->queueId = '';
        $this->matchDuration = new DateTime('@0');
        $this->matchTimestamp = new DateTime();
        $currentDate = new DateTime();

        if (curl_error($ch)) {
            throw new Exception("Le serveur a rencontré un problème.");
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            var_dump(curl_getinfo($ch, CURLINFO_HTTP_CODE));
            throw new MatchNotFoundException('Le match '.urldecode($matchId)." n'a pas pu être chargé ou n'existe pas.");
        }

        $matchData = json_decode($response, true);

        $summoners = $matchData["info"]["participants"];
        $this->queueId = $matchData["info"]["queueId"];
        $this->matchDuration->add(new DateInterval('PT' . $matchData["info"]["gameDuration"] . 'S'));
        $this->matchDuration->format('i:s');
        $this->matchTimestamp->setTimestamp((int) ($matchData["info"]["gameStartTimestamp"] / 1000));
        $this->matchTimestamp->format("Y-m-d H:i:s");
        $diff = $currentDate->diff($this->matchTimestamp);
        $this->diffInSeconds = $diff->s + ($diff->i * 60) + ($diff->h * 3600) + ($diff->d * 86400);

        $this->maxDamage = count($summoners) > 0 ? $summoners[0]["totalDamageDealtToChampions"] : null;
        $this->maxGolds = count($summoners) > 0 ? $summoners[0]["goldEarned"] : null;
        $this->winnerTeam = $matchData["info"]["teams"][0]["win"] === true ? 100 : 200;

        foreach ($summoners as $summoner) {
            $items = [];
            for ($i = 0; $i < 6; $i++) {
                $items[] = $summoner["item".$i];
            }

            if ($summoner["totalDamageDealtToChampions"] > $this->maxDamage) $this->maxDamage = $summoner["totalDamageDealtToChampions"];
            if ($summoner["goldEarned"] > $this->maxGolds) $this->maxGolds = $summoner["goldEarned"];

            $this->participants[] = [
                "riotIdGameName" => $summoner["riotIdGameName"],
                "riotIdTagline"=> $summoner["riotIdTagline"],
                "summonerName" => $summoner["summonerName"],
                "summonerId" => $summoner["summonerId"],
                "summonerLevel" => $summoner["summonerLevel"],
                "summoenrIcon" => $summoner["profileIcon"],
                "puuid" => $summoner["puuid"],
                "championName" => $summoner["championName"],
                "championId" => $summoner["championId"],
                "championLevel" => $summoner["champLevel"],
                "win" => $summoner["win"],
                "role" => $summoner["teamPosition"],
                "kda" => ($summoner["challenges"]["kda"]),
                "kills" => $summoner["kills"],
                "deaths" => $summoner["deaths"],
                "assists" => $summoner["assists"],
                "totalCs" => $summoner["totalMinionsKilled"] + $summoner["neutralMinionsKilled"],
                "golds" => $summoner["goldEarned"],
                "damages" => $summoner["totalDamageDealtToChampions"],
                "visionScore" => $summoner["visionScore"],
                "items" => $items,
                "summoner1Id" => $summoner["summoner1Id"],
                "summoner2Id" => $summoner["summoner2Id"],
                "team" => $summoner["teamId"] === 100 ? "Blue" : "Red"
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
            'participants' => $this->participants,
            'maxDamage' => $this->maxDamage,
            'maxGolds' => $this->maxGolds,
            'queueId' => $this->queueId,
            'teams' => $this->teams,
            'winnerTeam' => $this->winnerTeam
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