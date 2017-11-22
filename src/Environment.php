<?php
/**
 * SpeciesSimulator/Environment.php.
 *
 * @package   cykonetic\SpeciesSimulator
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types = 1);

namespace cykonetic\SpeciesSimulator;

/**
 * An instance of the given habitat for the given month.
 */
class Environment
{
    /**
     * @var int current remaining food
     */
    protected $current_food;
    /**
     * @var int current remaining water
     */
    protected $current_water;
    /**
     * @var int current temperature
     */
    protected $current_temp;

    /**
     * Simulated Environmant.
     *
     * @param Habitat $habitat
     * @param int     $month
     *
     * @return Environment
     */
    public function __construct(Habitat $habitat, int $month)
    {
        $this->current_food  = $habitat->getFood();
        $this->current_water = $habitat->getWater();
        $this->current_temp  = $habitat->getTemperature($month);
    }

    /**
     * Gets current food.
     *
     * @return int current food
     */
    public function getFood() : int
    {
        return $this->current_food;
    }

    /**
     * Gets current water.
     *
     * @return int current water
     */
    public function getWater() : int
    {
        return $this->current_water;
    }

    /**
     * Gets current temperature.
     *
     * @return int current temperature
     */
    public function getTemperature() : int
    {
        return $this->current_temp;
    }

    /**
     * Attempts to provide given number of food units.
     *
     * @param int     $units amount of food requested
     *
     * @return bool    true if units are available, otherwise false
     */
    public function provideFood(int $units) : bool
    {
        $this->current_food -= $units;

        return (0 <= $this->current_food);
    }

    /**
     * Attempts to provide given number of water units.
     *
     * @param int     $units amount of water requested
     *
     * @return bool    true if units are available, otherwise false
     */
    public function provideWater(int $units) : bool
    {
        $this->current_water -= $units;

        return (0 <= $this->current_water);
    }
}
