<?php

namespace App\Presentation\Console\Core\Component\Ball;

use App\Core\Component\Ball\Application\Repository\BallRepositoryInterface;
use App\Core\Component\Ball\Application\Service\BallService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ballCommand extends ContainerAwareCommand
{
    const maxIteration = 4;

    const sleepTime = 500;

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
        for ($i = 0; $i < self::maxIteration; ++$i) {
            $ball = $this->ballRepository->get();

            if ('-' === $ball->getUUID()) {
                $this->ballservice->create($ball);
                $this->ballRepository->update($ball);
            }

            $this->ballservice->run($ball);

            $this->ballRepository->update($ball);

            usleep(self::sleepTime * 1000);
        }
    }
}
