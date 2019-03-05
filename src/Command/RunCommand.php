<?php

namespace App\Command;

use App\Entities\Ball;
use App\Entities\Player;
use App\Entities\StadiumInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends ContainerAwareCommand
{
    /** @var StadiumInterface */
    private $stadium;

    /** @var PlayerInterface */
    private $player;

    /** @var BallInterface */
    private $ball;

    public function __construct(StadiumInterface $stadium, Ball $ball, Player $player)
    {
        $this->stadium = $stadium;
        $this->ball = $ball;
        $this->player = $player;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run')
            ->setDescription('run an entity forever');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < 2; $i++) {
            $type = getEnv('APP_TYPE');
            switch ($type) {
                case 'ball':
                    $this->ball->load();
                    $this->ball->run();
                    break;
                case 'player':
                    $this->player->load();
                    $this->player->run();
                    break;
            }
            sleep(0.2);
        }
    }
}

