<?php

use PHPUnit\Framework\TestCase;
use cykonetic\SpeciesSimulator\{Environment, Habitat};

class EnvironmentTest extends TestCase
{
    public function environmentProvider()
    {
        $temps = ['winter' => 15, 'spring' => 45, 'summer' => 75, 'fall' => 45];
        $habitat = new Habitat('testHabitat', 300, 200, $temps);

        $environments = array();

        for ($month = 1; $month <= 12; $month++) {
            $environment = new Environment($habitat, $month);
            $environments[] = array($environment);
        }

        return $environments;
    }

    /**
     * @dataProvider environmentProvider
     */
    public function testGetFood(Environment $environment)
    {
        $this->assertEquals(300, $environment->getFood());
    }

    /**
     * @dataProvider environmentProvider
     */
    public function testProvideFood(Environment $environment)
    {
        $this->assertTrue($environment->provideFood(300));
        $this->assertFalse($environment->provideFood(1));
    }

    /**
     * @dataProvider environmentProvider
     */
    public function testGetWater(Environment $environment)
    {
        $this->assertEquals(200, $environment->getWater());
    }

    /**
     * @dataProvider environmentProvider
     */
    public function testProvideWater(Environment $environment)
    {
        $this->assertTrue($environment->provideWater(200));
        $this->assertFalse($environment->provideWater(1));
    }
}
