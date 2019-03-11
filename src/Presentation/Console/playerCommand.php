<?php

namespace App\Presentation\Console;

use App\Core\Component\Player;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class playerCommand extends ContainerAwareCommand
{
    /** @var PlayerInterface */
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run:player')
            ->setDescription('run a player worker');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < 1; ++$i) {
            $this->player->load();
            $this->player->run();
            sleep(0.5);
        }
    }
}
