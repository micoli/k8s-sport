<?php

declare(strict_types=1);

namespace test\Unit\App\Core\Component;

use App\Core\Component\Stadium\Domain\Stadium;
use PHPUnit\Framework\TestCase;

final class stadiumTest extends TestCase
{
    /** @var Stadium */
    private $stadium;

    public function setUp(): void
    {
        $this->stadium = new Stadium();
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
