<?php
namespace cykonetic\SpeciesSimulator;

class Environment
{

    protected $currentFood;
    protected $currentWater;
    protected $currentTemp;

    public function __construct(Habitat $habitat, $month)
    {
        $this->currentFood  = $habitat->getFood();
        $this->currentWater = $habitat->getWater();
        $this->currentTemp  = $habitat->getTemperature($month);
    }

    public function getFood()
    {
        return $this->currentFood;
    }

    public function getWater()
    {
        return $this->currentWater;
    }

    public function getTemperature()
    {
        return $this->currentTemp;
    }

    public function consumedFood(Species $species)
    {
        $this->currentFood -= $species->getRequiredFood();

        return (0 <= $this->currentFood);
    }

    public function consumedWater(Species $species)
    {
        $this->currentWater -= $species->getRequiredWater();

        return (0 <= $this->currentWater);
    }
}
