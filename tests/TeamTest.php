<?php
namespace App\Tests\Entity;

use App\Entity\Team;
use App\Entity\Player;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    public function testAddPlayer()
    {
        $team = new Team();
        $player = new Player();

        $this->assertCount(0, $team->getPlayers());

        $team->addPlayer($player);

        $this->assertCount(1, $team->getPlayers());
        $this->assertSame($team, $player->getTeam());
    }

    public function testRemovePlayer()
    {
        $team = new Team();
        $player = new Player();
        $team->addPlayer($player);

        $this->assertCount(1, $team->getPlayers());

        $team->removePlayer($player);

        $this->assertCount(0, $team->getPlayers());
        $this->assertNull($player->getTeam());
    }

    // Ajoutez d'autres tests ici pour vérifier les autres fonctionnalités de la classe Team
}