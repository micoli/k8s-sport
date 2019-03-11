<?php

namespace App\Presentation\Console;

use App\Core\Port\StadiumInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class stadiumCommand extends ContainerAwareCommand
{
    /** @var StadiumInterface */
    private $stadium;

    public function __construct(StadiumInterface $stadium)
    {
        $this->stadium = $stadium;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:run:stadium')
            ->setDescription('run an entity forever');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        sleep(10);
    }
}
