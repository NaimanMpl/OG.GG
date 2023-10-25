<?php

namespace App\Factories;

use App\Models\Rank;

class RankFactory {

    public static function createRank(string $tier, string $rank) {
        switch ($tier) {
            case "IRON":
                return new Rank("Fer", $rank);
            case "BRONZE":
                return new Rank("Bronze", $rank);
            case "SILVER":
                return new Rank("Argent", $rank);
            case "GOLD":
                return new Rank("Or", $rank);
            case "PLATINUM":
                return new Rank("Platine", $rank);
            case "EMERALD":
                return new Rank("Emeraude", $rank);
            case "DIAMOND":
                return new Rank("Diamant", $rank);
            case "MASTER":
                return new Rank("Maitre", $rank);
            case "GRANDMASTER":
                return new Rank("Grand Maitre", $rank);
            case "CHALLENGER":
                return new Rank("Challenger", $rank);
            default:
                return new Rank("Unranked", 0);
        }
    }

}

?>