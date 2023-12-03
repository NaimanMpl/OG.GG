<?php

namespace App\Models;

enum Channel: int {

    case UNRANKED = 0;
    case IRON = 1;
    case BRONZE = 2;
    case SILVER = 3;
    case GOLD = 4;
    case PLATINIUM = 5;
    case EMERALD = 6;
    case DIAMOND = 7;
    case MASTER = 8;
    case GRANDMASTER = 9;
    case CHALLENGER = 10;

    public static function contains(int $value): bool {
        foreach (Channel::cases() as $channel) {
            if ($value === $channel->value) {
                return true;
            }
        }
        return false;
    }

}

?>