<?php
/**
 * SpeciesSimulator/Habitat.php.
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
 * Provides environments for animals.
 *
 * The habitat contains information regarding food and water levels
 * as well as seasonal temperature variations.
 */
class Habitat
{
    /**
     * Spring flag
     */
    const SPRING = 'spring';
    /**
     * Summer flag
     */
    const SUMMER = 'summer';
    /**
     * Fall flag
     */
    const FALL   = 'fall';
    /**
     * Winter flag
     */
    const WINTER = 'winter';

    /**
     * Returns the season flag for the given month.
     *
     * @param int     $month month
     *
     * @return string    season flag
     */
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

    /**
     * @var string habitat's name (forrest, plains, etc.)
     */
    protected $name;
    /**
     * @var int monthly food requirement
     */
    protected $food;
    /**
     * @var int monthly water requirement
     */
    protected $water;
    /**
     * @var int[] season : temperature map
     */
    protected $avg_temp;

    /**
     * Simulated Environment Provider.
     *
     * @param string  $name
     * @param int     $monthly_food
     * @param int     $monthly_water
     * @param array   $average_temperature
     *
     * @return Habitat
     */
    public function __construct(string $name, int $monthly_food, int $monthly_water, array $average_temperature)
    {
        $this->name = $name;
        $this->food = $monthly_food;
        $this->water = $monthly_water;
        $this->avg_temp = $average_temperature;
    }

    /**
     * Gets habitat name.
     *
     * @return string habitat name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Gets monthly food provision.
     *
     * @return int food provision
     */
    public function getFood() : int
    {
        return $this->food;
    }

    /**
     * Gets monthly water provision.
     *
     * @return int water provision
     */
    public function getWater()
    {
        return $this->water;
    }

    /**
     * Gets monthly temperature.
     *
     * @param int $month to retrieve temperature for
     *
     * @return int temperature
     */
    public function getTemperature(int $month) : int
    {
        //normal fluctuation of 5 degrees with .5% chance of 15
        $max_range = !rand(0, 199) ? 15 : 5;
        //calculate the deviation
        $deviation = rand(0, $max_range * 2) - $max_range;
        //apply it to the average temperature and return
        return $this->avg_temp[self::getSeason($month)] + $deviation;
    }
}
