<?php

namespace App\Presentation\Console;

use App\Core\Component\Stadium\Application\Repository\StadiumRepositoryInterface;
use App\Core\Component\Stadium\Application\Service\StadiumService;
use App\Core\Port\StadiumInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class stadiumCommand extends ContainerAwareCommand
{
    /** @var StadiumInterface */
    private $stadium;

    public function __construct(StadiumService $stadiumservice, StadiumRepositoryInterface $stadiumRepository)
    {
        $this->stadiumservice = $stadiumservice;
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
        for ($i = 0; $i < 1; ++$i) {
            $stadium = $this->stadiumRepository->get();
            $this->stadiumservice->run($stadium);
            $this->stadiumRepository->update($stadium);

            sleep(0.5);
        }
    }
}
