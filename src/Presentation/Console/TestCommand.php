<?php

namespace App\Presentation\Console;

use App\Infrastructure\Communication\Http\HttpClientInterface;
use App\Infrastructure\WebSocket\Client\WsClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
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
        //print_r($this->httpClient->send('GET','https://jsonplaceholder.typicode.com/users',null));
        print_r($this->httpClient->send('POST', 'https://jsonplaceholder.typicode.com/posts', [
          'title' => 'foo',
          'body' => 'bar',
          'userId' => 1,
        ]));
        die();
        $ws = new WsClient([
            'host' => '127.0.0.1',
            'port' => 81,
            'path' => '',
        ]);
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
