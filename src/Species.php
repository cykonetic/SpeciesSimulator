<?php
/**
 * DatalitSim/lib/Species.php.
 *
 * Species defines the abilities for a group of related creatures
 *
 * PHP version 5.3
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  CoreApi
 * @package   DatalotSim
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright 2017 Nickolai Bush
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   0.0.1
 * @see \DatalotSim\Species
 * @since     0.0.1
 */

/**
 * DatalotSim.
 */
namespace cykonetic\SpeciesSimulator;

/**
 * Species
 *
 * Exposes configuration values for a species
 */
class Species
{
    // {{{constants

    const EATS      = 'monthly_food_consumption';
    const DRINKS    = 'monthly_water_consumption';
    const LIVES     = 'life_span';
    const MIN_AGE   = 'minimum_breeding_age';
    const MAX_AGE   = 'maximum_breeding_age';
    const GESTATION = 'gestation_period';
    const MIN_TEMP  = 'minimum_temperature';
    const MAX_TEMP  = 'maximum_temperature';
    const ATTRIBUTES = ['gestation_period',
                        'life_span',
                        'maximum_breeding_age',
                        'maximum_temperature',
                        'minimum_breeding_age',
                        'minimum_temperature',
                        'monthly_food_consumption',
                        'monthly_water_consumption'
                       ];
    /**
     * @var string The species' name.
     */
    protected $name;

    /**
     *@var array Data about how the species interacts with the environment.
     */
    protected $attributes;
    
    /**
     * Species Constructor.
     *
     * @param array $speciesConfig Data about a type of creature
     */
    public function __construct(string $name, array $attributes)
    {
        $keys = array_keys($attributes);
        sort($keys);
        if ($keys !== Species::ATTRIBUTES) {
            throw new Exception('`attributes` improperly defined');
        }
        
        $this->name = $name;
    }

    /**
     * Return the name of the species.
     *
     * @return string species name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Return monthly food requirement.
     *
     * @return int monthly food requirement
     */
    public function getRequiredFood() : int
    {
        return $this->attributes[self::EATS];
    }

    /**
     * Return monthly water requirement.
     *
     * @return int monthly water requirement
     */
    public function getRequiredWater() : int
    {
        return $this->attributes[self::DRINKS];
    }

    /**
     * Return the highest survivable temperature.
     *
     * @return int highest survivable 0temperature
     */
    public function getMaximumTolerance() : int
    {
        return $this->attributes[self::MAX_TEMP];
    }

    /**
     * Return the lowest survivable temperature.
     *
     * @return int lowest survivable temperature
     */
    public function getMinimumTolerance() : int
    {
        return $this->attributes[self::MIN_TEMP];
    }

    /**
     * Return the oldest breeding age (years).
     *
     * @return int oldest breeding age (years)
     */
    public function getMaximumBreeding() : int
    {
        return $this->attributes[self::MAX_AGE];
    }

    /**
     * Return the youngest breeding age (years).
     *
     * @return int youngest breeding age (years)
     */
    public function getMinimumBreeding() : int
    {
        return $this->attributes[self::MIN_AGE];
    }

    /**
     * Return the gestation period (months).
     *
     * @return int gestation period (months)
     */
    public function getGestationPeriod() : int
    {
        return $this->attributes[self::GESTATION];
    }

    /**
     * Return the oldest age possible.
     *
     * @return int oldest age possible
     */
    public function getMaximumAge() : int
    {
        return $this->attributes[self::LIVES];
    }
}
