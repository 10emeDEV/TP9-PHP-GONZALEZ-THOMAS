<?php

declare(strict_types=1);

namespace App\MatchMaker\Player;

class BlitzPlayer extends Player implements PlayerInterface
{
    public function __construct(string $name = 'anonymous', float $ratio = 1200.0)
    {
        parent::__construct($name, $ratio);
    }

    public function updateRatioAgainst(PlayerInterface $player, int $result): void
    {
        // Blitz players evolve 4x faster (128 = 32 * 4)
        $this->ratio += 128 * ($result - $this->probabilityAgainst($player));
    }
}
