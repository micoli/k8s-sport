<?php

namespace App\Core\Component;

use App\Core\Port\MovableInterface;
use App\Core\Port\PersistableInterface;
use App\Core\Port\PlayerInterface;
use App\Core\Port\PointInterface;
use App\Core\Port\RunableInterface;
use App\Infrastructure\Communication\Http\HttpClientInterface;
use App\Infrastructure\Persistence\DataInterface;
use App\Infrastructure\WebSocket\Client\WsClientInterface;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;

class Player implements MovableInterface, PlayerInterface, PersistableInterface, RunableInterface, \Serializable
{
    private $uuid;

    private $speed = 3;
    private $random = 0.3;

    private $team;

    private $name;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var Point */
    private $position;

    /** @var DataInterface */
    private $data;

    /** @var LoggerInterface */
    public $log;

    /** @var WsClientInterface */
    public $WsClient;

    public function __construct(HttpClientInterface $httpClient, DataInterface $data, LoggerInterface $log, WsClientInterface $WsClient)
    {
        $this->httpClient = $httpClient;
        $this->data = $data;
        $this->log = $log;
        $this->WsClient = $WsClient;
    }

    public function load()
    {
        $this->unserialize($this->data->load());
    }

    public function unserialize($dto)
    {
        $dto = json_decode($dto);
        $this->uuid = isset($dto->uuid) ? $dto->uuid : Uuid::uuid4();
        $this->name = isset($dto->name) ? $dto->name : 'name';
        $this->team = isset($dto->team) ? $dto->team : getenv('APP_TEAM');
        $this->position = isset($dto->position) ? new Point($dto->position->x, $dto->position->y) : new Point(0, 0);

        return $this;
    }

    public function serialize()
    {
        return json_encode([
            'type' => 'player',
            'uuid' => $this->uuid,
            'name' => $this->name,
            'team' => $this->team,
            'position' => [
                'x' => $this->getPosition()->getX(),
                'y' => $this->getPosition()->getY(),
            ],
        ]);
    }

    public function save()
    {
        $serialized = $this->serialize();
        $this->data->save($serialized);
        //$this->httpClient->send('PUT', 'http://stadium-php/stadium/player', $this->serialize());

        $this->WsClient->send('broadcast:'.$serialized);
        $this->WsClient->close();

        return $this;
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getPosition(): PointInterface
    {
        return $this->position;
    }

    public function run()
    {
        $ballPosition = new Point(0, 0);
        $positionStruct = $this->httpClient->send('GET', 'http://ball-php/ball/position', null);
        if (null !== $positionStruct) {
            $ballPosition->fromRaw($positionStruct);
            $this->moveTowards($ballPosition);
            $this->log->info(sprintf('player run %sx%s, ball :%sx%s',
                $this->getPosition()->getX(),
                $this->getPosition()->getY(),
                $ballPosition->getX(),
                $ballPosition->getY()
            ));
        }
    }

    private function hitBall(PointInterface $ballPosition)
    {
        $this->log->info(sprintf('player hit ball'));
        $this->httpClient->send('PUT', sprintf('http://ball-php/ball/hitto/%s/%s/%s/%s/%s',
            40,
            'blue' == $this->team ? 5 : (100 - 5),
            5,
            $this->getUUID(),
            $this->getName()
        ), null);
    }

    private function hitBallFrom(PointInterface $ballPosition)
    {
        $this->log->info(sprintf('player hit ball'));
        $this->httpClient->send('PUT', sprintf('http://ball-php/ball/hit/%s/%s/%s/%s/%s',
            $this->getPosition()->getX(),
            $this->getPosition()->getY(),
            5,
            $this->getUUID(),
            $this->getName()
        ), null);
    }

    private function getDistancedToHit()
    {
        return 2;
    }

    private function moveTowards(PointInterface $ballPosition)
    {
        $distanceDone = $this->position->moveTowards($ballPosition, $this->speed);

        $this->position->shiftXY(random_int(-100 * $this->random, 100 * $this->random) / 100, random_int(-100 * $this->random, 100 * $this->random) / 100);

        $this->save();

        if ($this->position->distanceTo($ballPosition) < $this->getDistancedToHit()) {
            $this->hitBall($ballPosition);
        }
    }
}
