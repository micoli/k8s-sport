<?php

declare(strict_types=1);

namespace test\Unit\App\Core\Component;

use App\Core\Component\Domain\Dimension;
use App\Core\Component\Domain\Stadium;
use App\Core\Port\StadiumInterface;
use App\Entities\BallFactory;
use App\Entities\PlayerFactory;
use App\Infrastructure\Persistence\DataMemory;
use PHPUnit\Framework\TestCase;

final class stadiumTest extends TestCase
{
    /** @var StadiumInterface */
    private $stadium;

    public function setUp(): void
    {
        $this->stadium = new Stadium(new Dimension(80, 80), new DataMemory(), $this->createMock(BallFactory::class), $this->createMock(PlayerFactory::class));
    }

    public function testDistributeGiveOnePlayer()
    {
        $this->assertNotNull($this->stadium->distributePlayer('red'));
    }

    public function testDistributeAlwaysNewPlayer()
    {
        $players = [];
        for ($i = 0; $i < 50; ++$i) {
            $player = $this->stadium->distributePlayer('red');
            $this->assertNotContains($player, $players);
            if (null != $player) {
                $players[] = $player;
            }
        }
    }
}
