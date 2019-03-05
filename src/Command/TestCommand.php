<?php

namespace App\Command;

use App\Entities\Player;
use App\Entities\Point;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    /** @var Player */
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:test')
            ->setDescription('run an entity forever');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new Point(0.0,0.0))->moveTowards((new Point(10.0,10.0)),3);
        (new Point(0.0,0.0))->moveTowards((new Point(10.0,10.0)),13);
        (new Point(0.0,0.0))->moveTowards((new Point(10.0,10.0)),213);
        $point = new Point(1.0,1.0);
        $destination = new Point(10.0,20.0);
        while($point->moveTowards($destination,2)>0){
            print sprintf("%3.2fx%3.2f => %3.2fx%3.2f\n",
                $point->getX(),$point->getY(),
                $destination->getX(),$destination->getY()
            );
        }

    }
}

