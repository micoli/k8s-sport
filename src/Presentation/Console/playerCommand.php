<?php

namespace App\Presentation\Console;

use App\Core\Component\Player\Application\Repository\PlayerRepositoryInterface;
use App\Core\Component\Player\Application\Service\PlayerService;
use App\Core\Port\PlayerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class playerCommand extends ContainerAwareCommand
{
    /** @var PlayerInterface */
    private $player;

    public function __construct(PlayerService $playerservice, PlayerRepositoryInterface $playerRepository)
    {
        $this->playerservice = $playerservice;
        $this->playerRepository = $playerRepository;
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
            $player = $this->playerRepository->get();
            $this->playerservice->run($player);
            $this->playerRepository->update($player);

            sleep(0.5);
        }
    }
}
