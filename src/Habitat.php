<?php
declare(strict_types = 1);

namespace cykonetic\SpeciesSimulator;

/**
 * Habitat
 *
 * Exposes configuration values for a habitat
 */
class Habitat
{

    const WINTER = 'winter';
    const SPRING = 'spring';
    const SUMMER = 'summer';
    const FALL   = 'fall';

    /**
     * Returns the season based on an integer 1...12.
     *
     * The raw month counter is modded by 12 and that result determines the season
     * * 0, 1, 2 - Winter
     * * 3, 4, 5 - Spring
     * * 6, 7, 8 - Summer
     * * 9, 10, 11 - Fall
     *
     * @param int $month
     * @return string the season for the month
     */
    public static function getSeason(int $month) : string
    {
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

    /**
     * Habitat constructor.
     * 
     * @param string $name
     */
    public function __construct(string $name, int $monthly_food, int $monthly_water, array $average_temperature)
    {
        $this->name = $name;
        $this->food = $monthly_food;
        $this->water = $monthly_water;
        $this->avg_temp = $average_temperature;
    }

    /**
     * Returns the name of the habitat.
     *
     * @return string habitat name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Returns the current food level.
     *
     * @return int amount of food per month
     */
    public function getFood() : int {
        return $this->food;
    }

    /**
     * Returns the current water level.
     * 
     * @return int amount of water per month
     */
    public function getWater() 
    {
        return $this->water;
    }

    /**
     * Returns the temperature with deviation for the given month.
     *
     * @param int $month
     * @return int random temperature based on month
     */
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
