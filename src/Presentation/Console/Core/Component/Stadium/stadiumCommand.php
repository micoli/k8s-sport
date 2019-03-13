<?php

namespace App\Presentation\Console\Core\Component\Stadium;

use App\Core\Component\Stadium\Application\Repository\StadiumRepositoryInterface;
use App\Core\Component\Stadium\Application\Service\StadiumService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class stadiumCommand extends ContainerAwareCommand
{
    const maxIteration = 100;

    const sleepTime = 500;

    /** @var StadiumService */
    private $stadiumService;

    /** @var StadiumRepositoryInterface */
    private $stadiumRepository;

    public function __construct(StadiumService $stadiumService, StadiumRepositoryInterface $stadiumRepository)
    {
        $this->stadiumService = $stadiumService;
        $this->stadiumRepository = $stadiumRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run:stadium')
            ->setDescription('run a stadium worker');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < self::maxIteration; ++$i) {
            $stadium = $this->stadiumRepository->get();

            $this->stadiumService->run($stadium);

            $this->stadiumRepository->update($stadium);

            usleep(self::sleepTime * 1000);
        }
    }
}
