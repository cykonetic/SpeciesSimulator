<?php
/**
 * SpeciesSimulator/Species.php
 *
 * @package   cykonetic\SpeciesSimulator
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types = 1);

namespace cykonetic\SpeciesSimulator;

use ReflectionClass;

/**
 * Meta information describing aspects of animals
 */
class Species
{
    /**
     * Gestation period flag
     */
    const GESTATION = 'gestation_period';
    /**
     * Life span flag
     */
    const LIVES     = 'life_span';
    /**
     * Maximum breeding age flag
     */
    const MAX_AGE   = 'maximum_breeding_age';
    /**
     * Maximum survivable temperature flag
     */
    const MAX_TEMP  = 'maximum_temperature';
    /**
     * Minimum breeding age flag
     */
    const MIN_AGE   = 'minimum_breeding_age';
    /**
     * Minimum survivable temperature flag
     */
    const MIN_TEMP  = 'minimum_temperature';
    /**
     * Monthly food requirement flag
     */
    const EATS      = 'monthly_food_consumption';
    /**
     * Monthly water requirement flag
     */
    const DRINKS    = 'monthly_water_consumption';

    /**
     * List of information used to simulate animals.
     *
     * @return string[] attributes string list
     */
    private static function getAttributeList()
    {
        $oClass = new ReflectionClass(get_class());
        $list = array_values($oClass->getConstants());
        sort($list);
        return $list;
    }

    /**
     * @var string Species' name
     */
    protected $name;
    /**
     * @var array associative array of species meta information
     */
    protected $attributes;

    public function __construct(string $name, array $attributes)
    {
        $keys = array_keys($attributes);
        sort($keys);
        if (self::getAttributeList() !== $keys) {
            throw new Exception('`attributes` improperly defined');
        }

        $this->attributes = $attributes;
        $this->name = $name;
    }

    /**
     * Gets the species name.
     *
     * @return string species' name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Gets species monthly food requirement.
     *
     * @return int required food units
     */
    public function getRequiredFood() : int
    {
        return $this->attributes[self::EATS];
    }

    /**
     * Gets species monthly water requirement.
     *
     * @return int required water units
     */
    public function getRequiredWater() : int
    {
        return $this->attributes[self::DRINKS];
    }

    /**
     * Gets species maximum survivable temperature
     *
     * @return int maximum survivable temperature
     */
    public function getMaximumTolerance() : int
    {
        return $this->attributes[self::MAX_TEMP];
    }

    /**
     * Gets species minimum survivable temperature
     *
     * @return int minimum survivable temperature
     */
    public function getMinimumTolerance() : int
    {
        return $this->attributes[self::MIN_TEMP];
    }

    /**
     * Gets species maximum breeding age
     *
     * @return int maximum breeding age
     */
    public function getMaximumBreeding() : int
    {
        return $this->attributes[self::MAX_AGE];
    }

    /**
     * Gets species minimum breeding age
     *
     * @return int minimum breeding age
     */
    public function getMinimumBreeding() : int
    {
        return $this->attributes[self::MIN_AGE];
    }

    /**
     * Gets species gestation period length.
     *
     * @return int gestation period
     */
    public function getGestationPeriod() : int
    {
        return $this->attributes[self::GESTATION];
    }

    /**
     * Get species maximum age.
     *
     * @return int maximum age
     */
    public function getMaximumAge() : int
    {
        return $this->attributes[self::LIVES];
    }
}
