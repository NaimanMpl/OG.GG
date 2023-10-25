<?php

namespace App\Models;

use App\Models\Rank;

class Queue {

    private int $wins;
    private int $losses;
    private int $leaguePoints;

    private Rank $rank;

    public function __construct(Rank $rank, int $wins, int $losses, int $leaguePoints) {
        $this->rank = $rank;
        $this->leaguePoints = $leaguePoints;
        $this->losses = $losses;
        $this->wins = $wins;
    }

    public function getWins(): int {
        return $this->wins;
    }

    public function getLosses(): int {
        return $this->losses;
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function toArray(): array {
        return [
            "tier" => $this->rank->getTier(),
            "rank" => $this->rank->getRank(),
            "wins" => $this->wins,
            "leaguePoints" => $this->leaguePoints,
            "losses" => $this->losses
        ];
    }

}

?>