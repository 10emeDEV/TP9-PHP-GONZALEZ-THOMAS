<?php

/*
 * This file is part of the OpenClassRoom PHP Object Course.
 *
 * (c) Grégoire Hébert <contact@gheb.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

abstract class AbstractPlayer
{
    public function __construct(protected string $name, protected float $ratio = 400.0)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function probabilityAgainst(self $player): float
    {
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }

    public function updateRatioAgainst(self $player, int $result): void
    {
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }

    public function getRatio(): float
    {
        return $this->ratio;
    }
}

/** @noinspection PhpUnused */
final class Player extends AbstractPlayer
{
}

final class QueuingPlayer extends AbstractPlayer
{
    public function __construct(AbstractPlayer $player, protected int $range = 1)
    {
        parent::__construct($player->getName(), $player->getRatio());
    }

    public function getRange(): int
    {
        return $this->range;
    }
}

final class Lobby
{
    /** @var array<QueuingPlayer> */
    public array $queuingPlayers = [];

    public function findOponents(QueuingPlayer $player): array
    {
        $minLevel = round($player->getRatio() / 100);
        $maxLevel = $minLevel + $player->getRange();

        return array_filter($this->queuingPlayers, static function (QueuingPlayer $potentialOponent) use ($minLevel, $maxLevel, $player) {
            $playerLevel = round($potentialOponent->getRatio() / 100);

            return $player !== $potentialOponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
        });
    }

    public function addPlayer(AbstractPlayer $player): void
    {
        $this->queuingPlayers[] = new QueuingPlayer($player);
    }

    /** @noinspection PhpParamsInspection */
    public function addPlayers(AbstractPlayer ...$players): void
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
    }
}

/** @var Player $greg */
$greg = new Player('greg', 400);
/** @var Player $jade */
$jade = new Player('jade', 476);

/** @noinspection PhpParamsInspection */
$lobby = new Lobby();
$lobby->addPlayers($greg, $jade);

var_dump($lobby->findOponents($lobby->queuingPlayers[0]));

exit(0);
