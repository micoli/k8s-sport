<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class runCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('application:run')
            ->setDescription('run an entitie forever')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'type of the entity'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');

        $output->writeln(sprintf("reading %s", $filename ?? "noname"));
    }
}

