<?php

declare(strict_types=1);

namespace App\MatchMaker;

use App\MatchMaker\Player\PlayerInterface;
use App\MatchMaker\Player\QueuingPlayerInterface;

interface LobbyInterface
{
    public function addPlayer(PlayerInterface $player): void;

    public function addPlayers(PlayerInterface ...$players): void;

    public function findOponents(QueuingPlayerInterface $player): array;
}
