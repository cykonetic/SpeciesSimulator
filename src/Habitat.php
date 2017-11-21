<?php
/**
 * SpeciesSimulator/Habitat.php
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
class Habitat
{
    const WINTER = 'winter';
    const SPRING = 'spring';
    const SUMMER = 'summer';
    const FALL   = 'fall';

    public static function getSeason(int $month) : string
    {
        //this also conveniently makes December == 0
        $month_of_year = $month % 12;

        if ($month_of_year < 3) {
            return self::WINTER;
        } elseif ($month_of_year < 6) {
            return self::SPRING;
        } elseif ($month_of_year < 9) {
            return self::SUMMER;
        }
        return self::FALL;
    }

    protected $name;
    protected $food;
    protected $water;
    protected $avg_temp;

    public function __construct(string $name, int $monthly_food, int $monthly_water, array $average_temperature)
    {
        $this->name = $name;
        $this->food = $monthly_food;
        $this->water = $monthly_water;
        $this->avg_temp = $average_temperature;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFood() : int
    {
        return $this->food;
    }

    public function getWater()
    {
        return $this->water;
    }

    public function getTemperature($month)
    {
        //normal fluctuation of 5 degrees with .5% chance of 15
        $max_range = !rand(0, 199) ? 15 : 5;
        //calculate the deviation
        $deviation = rand(0, $max_range * 2) - $max_range;
        //apply it to the average temperature and return
        return $this->avg_temp[self::getSeason($month)] + $deviation;
    }
}
