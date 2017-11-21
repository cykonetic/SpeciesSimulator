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
 * Summary
 */
class Species
{
    const GESTATION = 'gestation_period';
    const LIVES     = 'life_span';
    const MAX_AGE   = 'maximum_breeding_age';
    const MAX_TEMP  = 'maximum_temperature';
    const MIN_AGE   = 'minimum_breeding_age';
    const MIN_TEMP  = 'minimum_temperature';
    const EATS      = 'monthly_food_consumption';
    const DRINKS    = 'monthly_water_consumption';

    private static function getAttributeList()
    {
        $oClass = new ReflectionClass(get_class());
        $list = array_values($oClass->getConstants());
        sort($list);
        return $list;
    }

    protected $name;
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

    public function getName() : string
    {
        return $this->name;
    }

    public function getRequiredFood() : int
    {
        return $this->attributes[self::EATS];
    }

    public function getRequiredWater() : int
    {
        return $this->attributes[self::DRINKS];
    }

    public function getMaximumTolerance() : int
    {
        return $this->attributes[self::MAX_TEMP];
    }

    public function getMinimumTolerance() : int
    {
        return $this->attributes[self::MIN_TEMP];
    }

    public function getMaximumBreeding() : int
    {
        return $this->attributes[self::MAX_AGE];
    }

    public function getMinimumBreeding() : int
    {
        return $this->attributes[self::MIN_AGE];
    }

    public function getGestationPeriod() : int
    {
        return $this->attributes[self::GESTATION];
    }

    public function getMaximumAge() : int
    {
        return $this->attributes[self::LIVES];
    }
}
