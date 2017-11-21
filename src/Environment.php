<?php
namespace cykonetic\SpeciesSimulator;

class Environment
{
    protected $currentFood;
    protected $currentWater;
    protected $currentTemp;

    public function __construct(Habitat $habitat, int $month)
    {
        $this->currentFood  = $habitat->getFood();
        $this->currentWater = $habitat->getWater();
        $this->currentTemp  = $habitat->getTemperature($month);
    }

    public function getFood() : int
    {
        return $this->currentFood;
    }

    public function getWater() : int
    {
        return $this->currentWater;
    }

    public function getTemperature() : int
    {
        return $this->currentTemp;
    }

    public function provideFood(int $units) : bool
    {
        $this->currentFood -= $units;

        return (0 <= $this->currentFood);
    }

    public function provideWater(int $units) : bool
    {
        $this->currentWater -= $units;

        return (0 <= $this->currentWater);
    }
}
