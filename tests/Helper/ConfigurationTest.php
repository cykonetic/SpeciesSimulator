<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use cykonetic\SpeciesSimulator\Helper\Configuration;

class ConfigurationTest extends TestCase
{
    public function configArrayProvider()
    {
        return [
            'simple' => [
                [
                    'habitats' => [
                        [
                            'name' => 'testHabitat',
                            'monthly_food' => 1,
                            'monthly_water' => 1,
                            'average_temperature' => [
                                'summer' => 1,
                                'spring' => 0,
                                'fall' => 0,
                                'winter' => -1
                            ]
                        ]
                    ],
                    'species' => [
                        [
                            'name' => 'testSpecies',
                            'attributes' => [
                                'monthly_food_consumption' => 1,
                                'monthly_water_consumption' => 1,
                                'life_span' => 1,
                                'minimum_breeding_age' => 1,
                                'maximum_breeding_age' => 1,
                                'gestation_period' => 1,
                                'minimum_temperature' => 0,
                                'maximum_temperature' => 1
                            ]
                        ]
                    ],
                    'years' => 1,
                    'iterations' => 1
                ]
            ]
        ];
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testBuildFromConfigArray_Success(array $config_array)
    {
        $config = Configuration::BuildFromConfigArray($config_array);
        $this->assertInstanceOf('cykonetic\SpeciesSimulator\Helper\Configuration', $config);
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testGetHabitats(array $config_array)
    {
        $config = Configuration::BuildFromConfigArray($config_array);
        $habitats = $config->getHabitats();
        $this->assertCount(1, $habitats);
        $this->assertInstanceOf('cykonetic\SpeciesSimulator\Habitat', $habitats[0]);
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testGetSpecies(array $config_array)
    {
        $config = Configuration::BuildFromConfigArray($config_array);
        $species = $config->getSpecies();
        $this->assertCount(1, $species);
        $this->assertInstanceOf('cykonetic\SpeciesSimulator\Species', $species[0]);
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testGetLength(array $config_array)
    {
        $config = Configuration::BuildFromConfigArray($config_array);
        $this->assertEquals(12, $config->getLength());
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testGetIterations(array $config_array)
    {
        $config = Configuration::BuildFromConfigArray($config_array);
        $this->assertEquals(1, $config->getIterations());
    }

    /**
     * @dataProvider configArrayProvider
     * @expectedException        Exception
     * @expectedExceptionMessage Required key `habitats` is empty or not set.
     */
    public function testBuildFromConfigArray_FailNoHabitat($config_array)
    {
        unset($config_array['habitats']);
        $config = Configuration::BuildFromConfigArray($config_array);
    }

    /**
     * @dataProvider configArrayProvider
     * @expectedException        Exception
     * @expectedExceptionMessage Required key `species` is empty or not set.
     */
    public function testBuildFromConfigArray_FailNoSpecies($config_array)
    {
        unset($config_array['species']);
        $config = Configuration::BuildFromConfigArray($config_array);
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testBuildFromConfigArray_DefaultNoYears($config_array)
    {
        unset($config_array['years']);
        $config = Configuration::BuildFromConfigArray($config_array);
        $this->assertEquals(1200, $config->getLength());
    }

    /**
     * @dataProvider configArrayProvider
     */
    public function testBuildFromConfigArray_DefaultNoIterations($config_array)
    {
        unset($config_array['iterations']);
        $config = Configuration::BuildFromConfigArray($config_array);
        $this->assertEquals(10, $config->getIterations());
    }
}
