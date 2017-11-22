<?php
/**
 * SpeciesSimulator/Helper/PopulationData.php
 *
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types=1);

namespace cykonetic\SpeciesSimulator\Helper;

use cykonetic\SpeciesSimulator\Habitat;
use cykonetic\SpeciesSimulator\Species;

/**
 * Information about each population simulated.
 */
class PopulationData
{
    /**
     * @var Habitat population's habitat
     */
    protected $habitat;
    /**
     * @var Species population's species
     */
    protected $species;
    /**
     * @var int[] array of data collected
     */
    protected $raw_data;

    public function __construct(Habitat $habitat, Species $species)
    {
        $this->habitat = $habitat;
        $this->species = $species;
        $this->raw_data = array(
         'lived'          => 0,
         'died'           => 0,
         'max_population' => 0,
         'agg_population' => 0,
         'starvation'     => 0,
         'thirst'         => 0,
         'age'            => 0,
         'cold_weather'   => 0,
         'hot_weather'    => 0,
         'months'         => 0
        );
    }

    /**
     * Gets the habitat's name.
     *
     * @return string
     */
    public function getHabitatName() : string
    {
        return $this->habitat->getName();
    }

    /**
     * Gets the species' name.
     *
     * @return string
     */
    public function getSpeciesName() : string
    {
        return $this->species->getName();
    }

    /**
     * Gets months population survived.
     *
     * @return int
     */
    public function getLength() : int
    {
        return $this->raw_data['months'];
    }

    /**
     * Increment months simulated.
     *
     * @param int     $months to increment
     *
     * @return $this
     */
    public function incrementLength(int $months = 1) : self
    {
        $this->raw_data['months'] += $months;

        return $this;
    }

    /**
     * Gets the number of animals that lived
     *
     * @return int
     */
    public function getLived() : int
    {
        return $this->raw_data['lived'];
    }

    /**
     * Increment the number of animals that lived.
     *
     * @param int     $lives to increment
     *
     * @return $this
     */
    public function incrementLived(int $lives = 1) : self
    {
        $this->raw_data['lived'] += $lives;

        return $this;
    }

    /**
     * Gets the numbeer of animals that died.
     *
     * @return int
     */
    public function getDied() : int
    {
        return $this->raw_data['died'];
    }

    /**
     * Increments the number of animals that died.
     *
     * @param int     $deaths to increment
     *
     * @return $this
     */
    public function incrementDied(int $deaths) : self
    {
        $this->raw_data['died'] += $deaths;

        return $this;
    }

    /**
     * Gets the mortality rate.
     *
     * @return float
     */
    public function getMortalityRate() : float
    {
        return ($this->raw_data['died'] / $this->raw_data['lived']) * 100;
    }

    /**
     * Gets the maximum population.
     *
     * @return int
     */
    public function getMaxPopulation() : int
    {
        return $this->raw_data['max_population'];
    }

    /**
     * Sets the maximum population to population size if the population
     * size is greater than the current maximum.
     *
     * @param int     $population_size new population size
     *
     * @return $this
     */
    public function setMaxPopulation(int $population_size) : self
    {
        if ($this->raw_data['max_population'] < $population_size) {
            $this->raw_data['max_population'] = $population_size;
        }

        return $this;
    }

    /**
     * Gets the aggregate population.
     *
     * @return int
     */
    public function getAggeregatePopulaation() : int
    {
        return $this->raw_data['agg_population'];
    }

    /**
     * Increments the aggregate population.
     *
     * @param int     $population_size
     *
     * @return $this
     */
    public function incrementAggregatePopulation(int $population_size) : self
    {
        $this->raw_data['agg_population'] += $population_size;

        return $this;
    }

    /**
     * Gets the average population.
     *
     * By default it uses the months the population lived. This can be overriden by passing a
     * montht value to use
     *
     * @param int     $override_months optional months to compute average
     *
     * @return float
     */
    public function getAveragePopulation(int $override_months = null) : float
    {
        $months = $override_months ?? $this->raw_data['months'];

        return $this->raw_data['agg_population'] / $months;
    }

    /**
     * Gets the number of animals that starved.
     *
     * @return int
     */
    public function getStarved() : int
    {
        return $this->raw_data['starvation'];
    }

    /**
     * Increments the number of anials that starved.
     *
     * @param int     $deaths
     *
     * @return $this
     */
    public function incrementStarved(int $deaths) : self
    {
        $this->raw_data['starvation'] += $deaths;

        return $this;
    }

    /**
     * Gets the starvation rate.
     *
     * @return float
     */
    public function getStarvationRate() : float
    {
        return ($this->raw_data['starvation'] / $this->died) * 100;
    }

    /**
     * Gets the number of animals that dehydrated.
     *
     * @return int
     */
    public function getDehydrated() : int
    {
        return $this->raw_data['thirst'];
    }

    /**
     * Increments if number of animals that dehydrated.
     *
     * @param int     $deaths
     *
     * @return $this
     */
    public function incrementDehydrated(int $deaths) : self
    {
        $this->raw_data['thirst'] += $deaths;

        return $this;
    }

    /**
     * Gets the dehydration rate.
     *
     * @return float
     */
    public function getDehydrationRate() : float
    {
        return ($this->raw_data['thirst'] / $this->raw_data['died']) * 100;
    }

    /**
     * Gets the number of animals that died of natural causes.
     *
     * @return int
     */
    public function getNaturalCauses() : int
    {
        return $this->raw_data['age'];
    }

    /**
     * Increments the number of animals that died of natural causes.
     *
     * @param int     $deaths
     *
     * @return $this
     */
    public function incrementNaturalCauses(int $deaths) : self
    {
        $this->raw_data['age'] += $deaths;

        return $this;
    }

    /**
     * Gets the natural causes death rate.
     *
     * @return float
     */
    public function getNaturalCausesRate() : float
    {
        return ($this->raw_data['age'] / $this->raw_data['died']) * 100;
    }

    /**
     * Get the number of animals that froze to death.
     *
     * @return int
     */
    public function getFroze() : int
    {
        return $this->raw_data['cold_weather'];
    }

    /**
     * Increment the number of animals that froze to death.
     *
     * @param int     $deaths
     *
     * @return $this
     */
    public function incrementFroze(int $deaths) : self
    {
        $this->raw_data['cold_weather'] += $deaths;

        return $this;
    }

    /**
     * Get the froze to death rate.
     *
     * @return float
     */
    public function getFrozeRate() : float
    {
        return ($this->raw_data['cold_weather'] / $this->raw_data['died']) * 100;
    }

    /**
     * Get the number of animals that died of heat stroke.
     *
     * @return int
     */
    public function getOverheated() : int
    {
        return $this->raw_data['hot_weather'];
    }

    /**
     * Increments the number of animals that died of heat stroke.
     *
     * @param int     $deaths Description
     *
     * @return Type    Description
     */
    public function incrementOverheated(int $deaths) : self
    {
        $this->raw_data['hot_weather'] += $deaths;

        return $this;
    }

    /**
     * Get the death rate by heat stroke.
     *
     * @return float
     */
    public function getOverheatedRate() : float
    {
        return ($this->raw_data['hot_weather'] / $this->raw_data['died']) * 100;
    }

    /**
     * Get the raw data produced by the simulation.
     *
     * @return int[]
     */
    public function getRawData() : array
    {
        return $this->raw_data;
    }

    /**
     * Combines the results of two simulations.
     *
     * @param PopulationData $merge_data raw data to be merged
     *
     * @return $this
     */
    public function merge(PopulationData $merge_data) : self
    {
        $merge_raw_data = $merge_data->getRawData();
        foreach (array_keys($this->raw_data) as $key) {
            switch ($key) {
                case 'max_population':
                    if ($this->raw_data['max_population'] < $merge_raw_data['max_population']) {
                        $this->raw_data['max_population'] = $merge_raw_data['max_population'];
                    }
                    break;
                default:
                    $this->raw_data[$key] += $merge_raw_data[$key];
            }
        }

        return $this;
    }

    /**
     * Print results.
     *
     * @return void
     */
    public function render()
    {
        echo "$this->species_name\n";
        echo "\t$this->habitat_name\n";
        echo "\t\tAverage Population: ".sprintf('%01.1f', $this->getAveragePopulation())."\n";
        echo "\t\tMax Population: ".$this->getMaxPopulation()."\n";
        echo "\t\tMoratality Rate: ".sprintf('%01.2f%%', $this->getMortalityRate())."\n";
        echo "\t\tCause of Death:\n";
        echo "\t\t\tstarvation: ".sprintf('%01.2f%%', $this->getStarvationRate())."\n";
        echo "\t\t\tthirst: ".sprintf('%01.2f%%', $this->getDehydrationRate())."\n";
        echo "\t\t\tage: ".sprintf('%01.2f%%', $this->getNaturalCausesRate())."\n";
        echo "\t\t\tcold_weather: ".sprintf('%01.2f%%', $this->getFrozeRate())."\n";
        echo "\t\t\thot_weather: ".sprintf('%01.2f%%', $this->getOverHeatedRate())."\n";
    }
}
