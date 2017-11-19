<?php

namespace cykonetic\SpeciesSimulator;

class Species
{

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

    protected $name;
    protected $attributes;

    public function __construct(string $name, array $attributes)
    {
        $keys = array_keys($attributes);
        sort($keys);
        if ($keys !== Species::ATTRIBUTES) {
            throw new Exception('`attributes` improperly defined');
        }
        
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
