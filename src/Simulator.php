<?php
/**
 * 
 *
 * Simulation Class applies time to each species in each habitat and records the outcome
 *
 * PHP version 5.3
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  CoreApi
 * @package   SpeciesSimulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright 2017 Nickolai Bush
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   0.0.1
 * @see       DatalotSim\Species, DatalotSim\Habitat, DatalotSim\Population
 * @since     0.0.1
 */

namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Helper\PopulationStats;

/**
 * Driver for the configured species/habitat simulations.
 *
 * Runs each configured species throug each configured enviroment over
 * 12 * years "ticks" amd records the results.
 */

class Simulator {
	/**
	 * Array of habitats in which simulations occur.
	 *
	 * @var \Habitat[]
	 */
	protected $habitats;

	/**
	 * Arrray of species to simulate.
	 *
	 * @var \Species[]
	 */
	protected $species;

	/**
	 * Simulation length (months) length of simulation in month.
	 *
	 * @var int
	 */
	protected $length;

	/**
	 * Tracks the current state of a simulation.
	 *
	 * @var \Population[]
	 */
	protected $populations = array();

	/**
	 * Weather or not the simulations has been run yet.
	 *
	 * @var boolean simulation state
	 */
	protected $ran = false;
    protected $ready = false;

	/**
	 * Simulation constructor.
	 *
	 * @param \DatalotSim\Habitat[] $habitats all habitats to be simulated
	 * @param \DatalotSim\Species[] $species  all species to be simulated
	 * @param int                   $years    number of years to simulate
	 */
	public function __construct($config_yaml = null)
    {
        if ($config_yaml !== null) {
            
        }
        //array $habitats, array $species, $years
		$this->habitats = $habitats;
		$this->species  = $species;
		$this->length   = $years*12;
	}

	/**
	 * Executes configured simulaions.
	 *
	 * @return null
	 */
	public function run()
    {
		if (!$this->ran) {
			foreach ($this->habitats as $habitat) {
				foreach ($this->species as $species) {
					$population = new Population($habitat, $species);
					for ($tick = 0; $tick < $this->length; $tick++) {
						$population->simulate($tick+1);
					}
					$this->populations[] = $population;
				}
			}
			$this->ran = true;
		}
	}

	/**
	 * Retrieves populations stats for the simulation.
	 *
	 * @return \PopulationStats[]
	 */
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
