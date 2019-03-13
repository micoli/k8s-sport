<?php

namespace App\Presentation\Console\Core\Component\Player;

use App\Core\Component\Player\Application\Repository\PlayerRepositoryInterface;
use App\Core\Component\Player\Application\Service\PlayerService;
use App\Core\Component\Player\Domain\Player;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class playerCommand extends ContainerAwareCommand
{
    /** @var PlayerService */
    private $playerservice;

    /** @var PlayerRepositoryInterface */
    private $playerRepository;

    const maxIteration = 100;
    const sleepTime = 500;

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
        for ($i = 0; $i < self::maxIteration; ++$i) {
            /** @var Player $player */
            $player = $this->playerRepository->get();

            $this->playerservice->init($player);

            $this->playerservice->run($player);

            $this->playerRepository->update($player);

            usleep(self::sleepTime);
        }
    }
}
