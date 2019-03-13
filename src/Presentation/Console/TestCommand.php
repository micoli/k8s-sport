<?php

namespace App\Presentation\Console;

use App\Core\SharedKernel\Component\Point;
use App\Infrastructure\WebSocket\Client\WsClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TestCommand extends ContainerAwareCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('application:test')
            ->setDescription('sandbox command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        die();
        $ws = new WsClient('127.0.0.1',
            81,
            ''
        );
        $result = $ws->send('broadcast:aaaaaa');
        $ws->close();
        echo $result;
        echo 'ee';
        die();

        (new Point(0.0, 0.0))->moveTowards((new Point(10.0, 10.0)), 3);
        (new Point(0.0, 0.0))->moveTowards((new Point(10.0, 10.0)), 13);
        (new Point(0.0, 0.0))->moveTowards((new Point(10.0, 10.0)), 213);
        $point = new Point(1.0, 1.0);
        $destination = new Point(10.0, 20.0);
        while ($point->moveTowards($destination, 2) > 0) {
            echo sprintf("%3.2fx%3.2f => %3.2fx%3.2f\n",
                $point->getX(), $point->getY(),
                $destination->getX(), $destination->getY()
            );
        }
    }
}
