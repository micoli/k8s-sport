<?php

namespace App\Presentation\Console;

use App\Core\Component\Ball\Application\Repository\BallRepositoryInterface;
use App\Core\Component\Ball\Application\Service\BallService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ballCommand extends ContainerAwareCommand
{
    /** @var BallService */
    private $ballservice;

    /** @var BallRepositoryInterface */
    private $ballRepository;

    public function __construct(BallService $ballservice, BallRepositoryInterface $ballRepository)
    {
        $this->ballservice = $ballservice;
        $this->ballRepository = $ballRepository;
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
        for ($i = 0; $i < 8; ++$i) {
            $ball = $this->ballRepository->get();
            $this->ballservice->run($ball);
            $this->ballRepository->update($ball);
            sleep(0.5);
        }
    }
}
