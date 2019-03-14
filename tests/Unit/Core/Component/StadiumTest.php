<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Component;

use App\Core\Component\Stadium\Domain\Stadium;
use PHPUnit\Framework\TestCase;

final class StadiumTest extends TestCase
{
    /** @var Stadium */
    private $stadium;

    public function setUp(): void
    {
        $this->stadium = new Stadium();
    }

    public function testDistributeGiveOnePlayer()
    {
        $t = $this->stadium->distributePlayer('red');
        $this->assertIsArray($t);
    }

    public function testDistributeAlwaysNewPlayer()
    {
        $players = [];
        for ($i = 0; $i < 20; ++$i) {
            list($playerName, $icon) = $this->stadium->distributePlayer('red');
            $this->assertNotContains($playerName, $players);
            if (null !== $playerName) {
                $players[] = $playerName;
            }
        }
    }

    public function testUnserializeValidString()
    {
        $json = '{"surface":{"width":28,"height":32},"goalRangeWWidth":7,"distributedPlayers":["aa","bb","cc"]}';
        $this->stadium->unserialize($json);
        $this->assertEquals(28, $this->stadium->getSurface()->getWidth());
        $this->assertEquals(32, $this->stadium->getSurface()->getHeight());
        $this->assertEquals([14, 16], $this->stadium->getCenter()->getCoord());
        $this->assertEquals(3, $this->stadium->getDistributedPlayersCount());
        $this->assertEquals($json, $this->stadium->serialize());
    }

    public function testUnserializeUnValidString()
    {
        $this->expectException(\ErrorException::class);
        $this->stadium->unserialize('{"surface":{"width":123,"height":456},"goalRangeWWidth":7,"distributedPlayers":["aa","bb","cc"]}aaa');
    }
}
