<?php

use PHPUnit\Framework\TestCase;
use cykonetic\SpeciesSimulator\Animal;
use cykonetic\SpeciesSimulator\Environment;
use cykonetic\SpeciesSimulator\Habitat;
use cykonetic\SpeciesSimulator\Species;

class AnimalTest extends TestCase
{
    protected static $species;

    public static function setUpBeforeClass()
    {
        $attributes = array(
            'gestation_period' => 5,
            'life_span' => 25,
            'minimum_breeding_age' => 10,
            'maximum_breeding_age' => 20,
            'minimum_temperature' => 0,
            'maximum_temperature' => 50,
            'monthly_food_consumption' => 3,
            'monthly_water_consumption' => 1
        );

        self::$species = new Species('testSpecies', $attributes);
    }

    public function testIsFemale()
    {
        $animal = new Animal(self::$species, Animal::FEMALE);
        $this->assertTrue($animal->isFemale());

        $animal = new Animal(self::$species, Animal::MALE);
        $this->assertFalse($animal->isFemale());
    }

    public function testIsMale()
    {
        $animal = new Animal(self::$species, Animal::MALE);
        $this->assertTrue($animal->isMale());

        $animal = new Animal(self::$species, Animal::FEMALE);
        $this->assertFalse($animal->isMale());
    }

    /**
     * @expectedException \cykonetic\SpeciesSimulator\Exception\Starved
     */
    public function testSurvive_FailToEat()
    {
        $temps = array(
            'winter' => -20,
            'spring' => 25,
            'summer' => 70,
            'fall' => 25
        );
        $habitat = new Habitat('testHabitat', 1, 100, $temps);
        $environment = new Environment($habitat, 4);
        $animal = new Animal(self::$species);

        while (true) {
            $animal->survive($environment);
        }
    }

    /**
     * @expectedException \cykonetic\SpeciesSimulator\Exception\Dehydrated
     */
    public function testSurvive_FailToDrink()
    {
        $temps = array(
            'winter' => -20,
            'spring' => 25,
            'summer' => 70,
            'fall' => 25
        );
        $habitat = new Habitat('testHabitat', 100, 1, $temps);
        $environment = new Environment($habitat, 4);
        $animal = new Animal(self::$species);

        while (true) {
            $animal->survive($environment);
        }
    }

    /**
     * @expectedException \cykonetic\SpeciesSimulator\Exception\Froze
     */
    public function testSurvive_FailToTolerateCold()
    {
        $temps = array(
            'winter' => -20,
            'spring' => 25,
            'summer' => 70,
            'fall' => 25
        );
        $habitat = new Habitat('testHabitat', 100, 100, $temps);
        $environment = new Environment($habitat, 1);
        $animal = new Animal(self::$species);

        while (true) {
            $animal->survive($environment);
        }
    }

    /**
     * @expectedException \cykonetic\SpeciesSimulator\Exception\Overheated
     */
    public function testSurvive_FailToTolerateHot()
    {
        $temps = array(
            'winter' => -20,
            'spring' => 25,
            'summer' => 70,
            'fall' => 25
        );
        $habitat = new Habitat('testHabitat', 100, 100, $temps);
        $environment = new Environment($habitat, 7);
        $animal = new Animal(self::$species);

        while (true) {
            $animal->survive($environment);
        }
    }

    /**
     * @expectedException \cykonetic\SpeciesSimulator\Exception\NaturalCauses
     */
    public function testSurvive_FailToAge()
    {
        $temps = array(
            'winter' => -20,
            'spring' => 25,
            'summer' => 70,
            'fall' => 25
        );
        $habitat = new Habitat('testHabitat', 10, 10, $temps);
        $animal = new Animal(self::$species);

        while (true) {
            $environment = new Environment($habitat, 4);
            $animal->survive($environment);
        }
    }
}
