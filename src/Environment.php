<?php
/**
 * SpeciesSimulator/Environment.php
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
 * Summary
 */
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
