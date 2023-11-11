<?php

namespace App\Models;
use App\Exceptions\SummonerNotFoundException;
use App\Factories\RankFactory;
use Exception;
use App\Models\Queue;

class Summoner {

    private string $name;
    private string $puuid;
    private string $id;
    private int $level;
    private int $profileIconId;
    private array $matchesId;
    private ?Queue $soloQueue = null;
    private ?Queue $flexQueue = null;

    public function __construct(string $name) {
        $this->name = $name;
        $this->fetchSummonerData();
        $this->fetchRankedData();
        $this->fetchMatchHistoryData(0, 20);
    }

    public function fetchSummonerData() {
        $apiUrl = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/".urlencode($this->name)."?api_key=".$_ENV['RIOT_API_KEY'];
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            throw new SummonerNotFoundException("Le summoner ".urldecode($this->name)." n'existe pas !");
        }
        $summonerData = json_decode($response, true);
        $this->name = urldecode($summonerData['name']);
        $this->puuid = $summonerData['puuid'];
        $this->id = $summonerData['id'];
        $this->level = $summonerData['summonerLevel'];
        $this->profileIconId = $summonerData['profileIconId'];
    }

    public function fetchMatchHistoryData(int $start, int $count) {
        $apiUrl = "https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/".$this->puuid."/ids?start=".$start."&count=".$count."&api_key=".$_ENV['RIOT_API_KEY'];
        $matchesData = json_decode(file_get_contents($apiUrl), true);
        foreach ($matchesData as $matchId) {
            $this->matchesId[] = $matchId;
        }
    }

    public function fetchRankedData() {
        $apiUrl = 'https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/'.$this->id."?api_key=".$_ENV['RIOT_API_KEY'];
        $rankedData = json_decode(file_get_contents($apiUrl), true);
        if (count($rankedData) === 1) {
            $rank = RankFactory::createRank($rankedData[0]["tier"], $rankedData[0]["rank"]);
            
            if ($rankedData[0]["queueType"] === "RANKED_SOLO_5x5") {
                $this->soloQueue = new Queue($rank, $rankedData[0]["wins"], $rankedData[0]["losses"], $rankedData[0]["leaguePoints"]);
            } else {
                $this->flexQueueRank = new Queue($rank, $rankedData[0]["wins"], $rankedData[0]["losses"], $rankedData[0]["leaguePoints"]);
            }
        } else if (count($rankedData) === 2) {
            $soloQueueRank = RankFactory::createRank($rankedData[0]["tier"], $rankedData[0]["rank"]);
            $flexQueueRank = RankFactory::createRank($rankedData[1]["tier"], $rankedData[1]["rank"]);
            
            $this->soloQueue = new Queue($soloQueueRank, $rankedData[0]["wins"], $rankedData[0]["losses"], $rankedData[0]["leaguePoints"]);
            $this->flexQueue = new Queue($flexQueueRank, $rankedData[1]["wins"], $rankedData[1]["losses"], $rankedData[1]["leaguePoints"]);
        }
    }

    public function getProfileIconId() : int {
        return $this->profileIconId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPuuid(): string {
        return $this->puuid;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getLevel(): int {
        return $this->level;
    }

    public function getMatchesId(): array {
        return $this->matchesId;
    }

    public function toArray(): array {
        return [
            'name' => $this->getName(),
            'level' => $this->getLevel(),
            'profileIconId' => $this->getProfileIconId(),
            'queues' => [
                "soloQueue" => $this->soloQueue === null ? null : $this->soloQueue->toArray(),
                "flexQueue" => $this->flexQueue === null ? null : $this->flexQueue->toArray()
            ],
            'matches' => $this->matchesId
        ];
    }

}

?>

