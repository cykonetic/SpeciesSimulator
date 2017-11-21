<?php
/**
 * SpeciesSimulator/Simulator.php
 *
 * @package   cykonetic\SpeciesSimulator
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types = 1);

namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Helper\Configuration;
use cykonetic\SpeciesSimulator\Helper\PopulationStats;

/**
 * Summary
 */
class Simulator
{
    protected $habitats;
    protected $species;
    protected $length;
    protected $iterations;
    protected $populations = array();
    protected $ran = false;

    public function __construct(Configuration $config)
    {
        $this->habitats = $config->getHabitats();
        $this->species = $config->getSpecies();
        $this->length = $config->getLength();
        $this->iterations = $config->getIterations();
    }

    public function run()
    {
        if (!$this->ran) {
            for ($i = 0; $i < $this->iterations; $i++) {
                foreach ($this->habitats as $habitat) {
                    foreach ($this->species as $species) {
                        $this->populations[] = new Population($habitat, $species);
                    }
                }
                for ($tick = 0; $tick < $this->length; $tick++) {
                    $population->simulate($tick+1);
                }
            }
            $this->ran = true;
        }
    }

    public function getSimulationStats()
    {
        if (!$this->ran) {
            $this->run();
        }
        $stats = array();
        foreach ($this->populations as $population) {
            $stats[] = $population->getPopulationData();
        }
        return $stats;
    }
}
