<?php

namespace App\Models;
use App\Exceptions\SummonerNotFoundException;
use App\Factories\RankFactory;
use Exception;
use App\Models\Queue;

class Summoner {

    private string $name;
    private string $tag;
    private string $puuid;
    private string $id;
    private int $level;
    private int $profileIconId;
    private array $matchesId;
    private ?Queue $soloQueue = null;
    private ?Queue $flexQueue = null;

    public function __construct(string $name, string $tag) {
        $this->name = $name;
        $this->tag = $tag;
        $this->matchesId = [];
        $this->fetchAccountrData();
        $this->fetchSummonerData();
        $this->fetchRankedData();
        $this->fetchMatchHistoryData(0, 10);
    }

    public function fetchAccountrData() {
        $ch = curl_init();
        $apiUrl = "https://europe.api.riotgames.com/riot/account/v1/accounts/by-riot-id/".urlencode($this->name)."/".urlencode($this->tag);

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $headers = [
            'X-Riot-Token: '.$_ENV["RIOT_API_KEY"] 
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_error($ch)) {
            throw new Exception("Le serveur a rencontré un problème.");
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            throw new SummonerNotFoundException("Le summoner ".urldecode($this->name)." n'existe pas !");
        }

        $summonerData = json_decode($response, true);
        $this->name = urldecode($summonerData['gameName']);
        $this->puuid = $summonerData['puuid'];
        $this->tag = $summonerData['tagLine'];

    }

    public function fetchSummonerData() {
        $apiUrl = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/".$this->puuid."?api_key=".$_ENV['RIOT_API_KEY'];
        $summonerData = json_decode(file_get_contents($apiUrl), true);
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
        for ($i = 0; $i < count($rankedData); $i++) {
            if ($rankedData[$i]["queueType"] === "RANKED_SOLO_5x5") {
                $soloQueueRank = RankFactory::createRank($rankedData[$i]["tier"], $rankedData[$i]["rank"]);
                $this->soloQueue = new Queue($soloQueueRank, $rankedData[$i]["wins"], $rankedData[$i]["losses"], $rankedData[$i]["leaguePoints"]);
            } else if ($rankedData[$i]["queueType"] === "RANKED_FLEX_SR") {
                $flexQueueRank = RankFactory::createRank($rankedData[$i]["tier"], $rankedData[$i]["rank"]);
                $this->flexQueue = new Queue($flexQueueRank, $rankedData[$i]["wins"], $rankedData[$i]["losses"], $rankedData[$i]["leaguePoints"]);
            }
        }
    }

    public function getProfileIconId() : int {
        return $this->profileIconId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getTag(): string {
        return $this->tag;
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
            'tag' => $this->tag,
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

