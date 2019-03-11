<?php

namespace App\Presentation\Console;

use App\Core\Component\Ball;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ballCommand extends ContainerAwareCommand
{
    /** @var BallInterface */
    private $ball;

    public function __construct(Ball $ball)
    {
        $this->ball = $ball;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run:ball')
            ->setDescription('run a ball worker');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < 1; ++$i) {
            $this->ball->load();
            $this->ball->run();
            sleep(0.5);
        }
    }
}
