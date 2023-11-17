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
    private string $queueId;
    private DateTime $matchDuration;
    private DateTime $matchTimestamp;
    private int $diffInSeconds;

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
        $this->queueId = '';
        $this->matchDuration = new DateTime('@0');
        $this->matchTimestamp = new DateTime();
        $currentDate = new DateTime();

        if (curl_error($ch)) {
            throw new Exception("Le serveur a rencontré un problème.");
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            throw new MatchNotFoundException('Le match '.urldecode($matchId)." n'a pas pu être chargé ou n'existe pas.");
        }

        $matchData = json_decode($response, true);

        $summoners = $matchData["info"]["participants"];
        $this->queueId = $matchData["info"]["queueId"];
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
                "kda" => ($summoner["kills"] + $summoner["deaths"] + $summoner["assists"] / 3) != null ? sprintf("%.2f", ($summoner["kills"] + $summoner["deaths"] + $summoner["assists"] / 3)) : 0,
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
            'participants' => $this->participants,
            'queueId' => $this->queueId
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