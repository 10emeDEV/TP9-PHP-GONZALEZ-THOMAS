<?php

declare(strict_types=1);

namespace App\MatchMaker;

use App\MatchMaker\Player\PlayerInterface;
use App\MatchMaker\Player\QueuingPlayerInterface;
use App\MatchMaker\Player\QueuingPlayer;

use App\MatchMaker\LobbyInterface;

class Lobby implements LobbyInterface
{
    /** @var array<QueuingPlayer> */
    public array $queuingPlayers = [];

    public function findOponents(QueuingPlayerInterface $player): array
    {
        $minLevel = round($player->getRatio() / 100);
        $maxLevel = $minLevel + $player->getRange();

        return array_filter($this->queuingPlayers, static function (QueuingPlayer $potentialOponent) use ($minLevel, $maxLevel, $player) {
            $playerLevel = round($potentialOponent->getRatio() / 100);

            return $player !== $potentialOponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
        });
    }

    public function addPlayer(PlayerInterface $player): void
    {
        $this->queuingPlayers[] = new QueuingPlayer($player);
    }

    public function addPlayers(PlayerInterface ...$players): void
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
    }
}
