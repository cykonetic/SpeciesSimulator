<?php

use PHPUnit\Framework\TestCase;
use cykonetic\SpeciesSimulator\Species;

class SpeciesTest extends TestCase
{
    public function testGetAttributeList()
    {
        $expected = array('gestation_period',
                          'life_span',
                          'maximum_breeding_age',
                          'maximum_temperature',
                          'minimum_breeding_age',
                          'minimum_temperature',
                          'monthly_food_consumption',
                          'monthly_water_consumption'
                         );

        $oSpecies = new ReflectionClass('cykonetic\SpeciesSimulator\Species');
        $oAttributeList = $oSpecies->getMethod('getAttributeList');
        $oAttributeList->setAccessible(true);

        $testing = $oAttributeList->invoke(null);
        sort($testing);

        $this->assertEquals($expected, $testing);
    }

    public function speciesParamProvider()
    {
        $attributes = array(
            'gestation_period' => 9,
            'life_span' => 75,
            'maximum_breeding_age' => 69,
            'maximum_temperature' => 100,
            'minimum_breeding_age' => 18,
            'minimum_temperature' => -20,
            'monthly_food_consumption' => 3,
            'monthly_water_consumption' => 1
        );

        return [['testSpecies', $attributes]];
    }
    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetGestationPeriod(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['gestation_period'], $species->getGestationPeriod());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetMaximumAge(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['life_span'], $species->getMaximumAge());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetMaximumBreeding(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['maximum_breeding_age'], $species->getMaximumBreeding());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetMinimumBreeding(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['minimum_breeding_age'], $species->getMinimumBreeding());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetMaximumTolerance(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['maximum_temperature'], $species->getMaximumTolerance());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetMinimumTolerance(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['minimum_temperature'], $species->getMinimumTolerance());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetRequiredFood(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['monthly_food_consumption'], $species->getRequiredFood());
    }

    /**
     * @dataProvider speciesParamProvider
     */
    public function testGetRequiredWater(string $name, array $attributes)
    {
        $species = new Species($name, $attributes);
        $this->assertEquals($attributes['monthly_water_consumption'], $species->getRequiredWater());
    }
}
