<?php

namespace App\Models;

class Rank {

    private string $tier;
    private string $rank;

    public function __construct(string $tier, string $rank) {
        $this->tier = $tier;
        $this->rank = $rank;
    }

    public function getTier(): string {
        return $this->tier;
    }

    public function getRank(): string {
        return $this->rank;
    }

}

?>