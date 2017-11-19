<?php
namespace cykonetic\SpeciesSimulator;

/**
 * Monthly instance of a habitat
 */
class Environment
{

    /**
     * current units of food available
     *
     * @var int
     */
    protected $currentFood;
    /**
     * current units of water available
     *
     * @var int
     */
    protected $currentWater;
    /**
     * current tempature in the habitat
     *
     * @var int
     */
    protected $currentTemp;

    /**
     *
     * @param Habitat $habitat type of habitat
     * @param int     $month   month of simulation
     */
    public function __construct(Habitat $habitat, $month)
    {
        $this->currentFood  = $habitat->getFood();
        $this->currentWater = $habitat->getWater();
        $this->currentTemp  = $habitat->getTemperature($month);
    }

    /**
     * getter for current food level
     *
     * @return int
     */
    public function getFood()
    {
        return $this->currentFood;
    }

    /**
     * getter for current water level
     *
     * @return int
     */
    public function getWater()
    {
        return $this->currentWater;
    }

    /**
     * getter for current tempature
     *
     * @return int
     */
    public function getTemperature()
    {
        return $this->currentTemp;
    }

    /**
     * calculates if given species can be fed
     *
     * @param  \Species $species species to feed
     * @return boolean
     */
    public function consumedFood(Species $species)
    {
        $this->currentFood -= $species->getRequiredFood();

        return (0 <= $this->currentFood);
    }

    /**
     * calculates if given species can drink
     *
     * @param  \Species $species species to feed
     * @return boolean
     */
    public function consumedWater(Species $species)
    {
        $this->currentWater -= $species->getRequiredWater();

        return (0 <= $this->currentWater);
    }
}
