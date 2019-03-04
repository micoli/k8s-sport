<?php

namespace App\Entities;

use App\Infrastructure\Data;

class Stadium implements StadiumInterface
{
    /** @var DimensionInterface */
    private $dimension;

    /** @var Data */
    private $data;

    /** @var BallFactory */
    private $ballFactory;

    /** @var PlayerFactory */
    private $playerFactory;

    private $balls = [];
    private $players = [];

    public function __construct(DimensionInterface $dimension, Data $data, BallFactory $ballFactory, PlayerFactory $playerFactory)
    {
        $this->dimension = $dimension;
        $this->data = $data;
        $this->ballFactory = $ballFactory;
        $this->playerFactory = $playerFactory;
    }

    public function load()
    {
        $this->fromStruct($this->data->load());
    }

    public function fromStruct($dto)
    {

        $this->balls = [];
        if (isset($dto->balls)) {
            foreach ($dto->balls as $oball) {
                $ball = $this->ballFactory->get();
                $ball->fromStruct($oball);
                $this->setBall($ball);
            }
        }

        $this->players = [];
        if (isset($dto->players)) {
            foreach ($dto->players as $oplayer) {
                $player = $this->playerFactory->get();
                $player->fromStruct($oplayer);
                $this->setPlayer($player);
            }
        }
    }

    private function save()
    {
        $this->data->save([
            'balls' => array_map(function(Ball $ball){
                return $ball->toStruct();
            },$this->balls),
            'players' => array_map(function(Player $player){
                return $player->toStruct();
            },$this->players)
        ]);
    }

    public function getDimension(): DimensionInterface
    {
        return $this->dimension;
    }

    public function getCenter(): Point
    {
        return $this->dimension->getCenter();
    }

    public function setBall(Ball $ball)
    {
        $this->balls[$ball->getUUID()] = $ball;
        $this->save();
    }

    public function removeBall($ballUUID)
    {
        if (array_key_exists($ballUUID, $this->balls)) {
            unset($this->balls[$ballUUID]);
            $this->save();
        }
    }

    public function getBalls()
    {
        return $this->balls;
    }

    public function setPlayer(Player $player)
    {
        $this->players[$player->getUUID()] = $player;
        $this->save();
    }

    public function removePlayer($playerUUID)
    {
        if (array_key_exists($playerUUID, $this->players)) {
            unset($this->players[$playerUUID]);
            $this->save();
        }
    }

    public function getPlayers()
    {
        return $this->players;
    }
}
