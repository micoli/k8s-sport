<?php

namespace App\Command;

use App\Entities\StadiumInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends ContainerAwareCommand
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
            ->setName('application:run')
            ->setDescription('run an entity forever')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = getEnv('type');

        print_r([$this->stadium->getDimension(),$type]);

        while (true){
            $output->writeln(sprintf("reading %s", $filename ?? "nonamea"));
            sleep(5);
        }
    }
}

