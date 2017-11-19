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
    public function testBuildFromArray($config_array)
    {
        $config = Configuration::BuildFromArray($config_array);
        $this->assertInstanceOf('Configuration', $config);
    }
}
