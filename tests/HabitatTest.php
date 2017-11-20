<?php

use PHPUnit\Framework\TestCase;
use cykonetic\SpeciesSimulator\Habitat;

class HabitatTest extends TestCase
{
    public function monthProvider()
    {
        return [
            'January' => [1, 'winter'],
            'February' => [2, 'winter'],
            'March' => [3, 'spring'],
            'April' => [4, 'spring'],
            'May' => [5, 'spring'],
            'June' => [6, 'summer'],
            'July' => [7, 'summer'],
            'August' => [8, 'summer'],
            'September' => [9, 'fall'],
            'October' => [10, 'fall'],
            'November' => [11, 'fall'],
            'December' => [12, 'winter']
        ];
    }

    /**
     * @dataProvider monthProvider
     */
    public function testGetSeason($month, $expected_season)
    {
        $this->assertEquals($expected_season, Habitat::getSeason($month));
    }

    public function habitatParamProvider()
    {
        $temps = ['winter' => 15, 'spring' => 45, 'summer' => 75, 'fall' => 45];

        return [['testHabitat', 300, 200, $temps]];
    }

    /**
     * @dataProvider habitatParamProvider
     */
    public function testGetName(string $name, int $food, int $water, array $avg_temp)
    {
        $habitat = new Habitat($name, $food, $water, $avg_temp);

        $this->assertEquals($name, $habitat->getName());
    }

    /**
     * @dataProvider habitatParamProvider
     */
    public function testGetFood(string $name, int $food, int $water, array $avg_temp)
    {
        $habitat = new Habitat($name, $food, $water, $avg_temp);

        $this->assertEquals($food, $habitat->getFood());
    }

    /**
     * @dataProvider habitatParamProvider
     */
    public function testGetWater(string $name, int $food, int $water, array $avg_temp)
    {
        $habitat = new Habitat($name, $food, $water, $avg_temp);

        $this->assertEquals($water, $habitat->getWater());
    }
    /**
     * @dataProvider habitatParamProvider
     */
    public function testGetTempature(string $name, int $food, int $water, array $avg_temp)
    {
        $habitat = new Habitat($name, $food, $water, $avg_temp);

        for ($month = 0; $month < 12; $month++) {

            $max_temperature = $min_temperature = $avg_temp[Habitat::getSeason($month)];
            $agg_temperature =  0;

            //$temps = array();
            for ($trial = 0; $trial < 1000; $trial++) {

                $temp = $habitat->getTemperature($month);
                //$temps[] = $temp;

                $agg_temperature += $temp;

                if ($max_temperature < $temp) {
                    $max_temperature = $temp;
                } elseif ($min_temperature > $temp) {
                    $min_temperature = $temp;
                }
            }

            $expected = $avg_temp[Habitat::getSeason($month)] - 15;
            $this->assertGreaterThanOrEqual($expected, $min_temperature);

            $expected = $avg_temp[Habitat::getSeason($month)] + 15;
            $this->assertLessThanOrEqual($expected, $max_temperature);

            $expected_min = $avg_temp[Habitat::getSeason($month)] - 5;
            $expected_max = $avg_temp[Habitat::getSeason($month)] + 5;
            $mean_temperature = $agg_temperature / 1000;

            $this->assertGreaterThanOrEqual($expected_min, $mean_temperature);
            $this->assertLessThanOrEqual($expected_max, $mean_temperature);
        }
    }
}
