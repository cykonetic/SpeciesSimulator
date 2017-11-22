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
 * Runs the simulations for the configured populations..
 */
class Simulator
{
    /**
     * @var \cykonetic\SpeciesSimulator\Habitat[] the set of habitats to simulate
     */
    protected $habitats;
    /**
     * @var \cykonetic\SpeciesSimulator\Species[] the set of species to simulate
     */
    protected $species;
    /**
     * @var int number of months for each simulation
     */
    protected $length;
    /**
     * @var int number of times to repeat each simulation
     */
    protected $iterations;
    /**
     * @var cykonetic\SpeciesSimulator\\Helper\PopulationData[] Description
     */
    protected $stats = array();
    /**
     * @var \cykonetic\SpeciesSimulator\Helper\PopulationData[][] summary stats
     */
    protected $summary = array();
    /**
     * @var bool true if the simulation is complate, false otherwise
     */
    protected $ran = false;

    /**
     * Simulator.
     *
     * Runs the configured simulkation
     *
     * @param \cykonetic\SpeciesSimulator\Helper\Configuration $config
     *
     * @return Simulator
     */
    public function __construct(Configuration $config)
    {
        $this->habitats = $config->getHabitats();
        $this->species = $config->getSpecies();
        $this->length = $config->getLength();
        $this->iterations = $config->getIterations();
    }

    /**
     * Runs the configured simulation.
     *
     * @return void
     */
    public function run()
    {
        if (!$this->ran) {
            foreach ($this->habitats as $habitat) {
                $this->summary[$habitat->getName()] = array();
                foreach ($this->species as $species) {
                    $this->summary[$habitat->getName()][$species->getName()] = new PopulationData($habitat, $species);
                    for ($iteration = 0; $iteration < $this->iterations; $iteration++) {
                        $population = new Population($habitat, $species);
                        for ($tick = 0; $tick < $this->length; $tick++) {
                            $population->simulate($tick + 1);
                            if (0 === $population->getPopulationSize()) {
                                break;
                            }
                        }
                        $this->stats[] = $population->getPopulationData();
                        $this->summary[$habitat->getName()][$species->getName()]->merge($population->getPopulationData());
                    }
                }
            }

            $this->ran = true;
        }
    }

    /**
     * Gets the population's logged data.
     *
     * @return \cykonetic\SpeciesSimulator\Helper\PopulationData[] population's logged data
     */
    public function getSimulationStats() : array
    {
        if (!$this->ran) {
            $this->run();
        }

        return $this->stats;
    }

    /**
     * Gets a summary of the population's logged data.
     *
     * @return \cykonetic\SpeciesSimulator\Helper\PopulationData[][] summary of population's logged data
     */
    public function getSummarySimulationStats() : array
    {
        if (!$this->ran) {
            $this->run();
        }

        return $this->summary;
    }
}
