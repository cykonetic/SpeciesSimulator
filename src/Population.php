<?php
/**
 * SpeciesSimulator/Population.php.
 *
 * @package   cykonetic\SpeciesSimulator
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types = 1);

namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Exception\Dehydrated;
use cykonetic\SpeciesSimulator\Exception\Froze;
use cykonetic\SpeciesSimulator\Exception\NaturalCauses;
use cykonetic\SpeciesSimulator\Exception\Overheated;
use cykonetic\SpeciesSimulator\Exception\Starved;
use cykonetic\SpeciesSimulator\Helper\PopulationData;

/**
 * Represents a group of animals as it survives a particular environment.
 */
class Population
{
    /**
     * @var Habitat population's environment provider
     */
    protected $habitat;
    /**
     * @var Species population's animal type
     */
    protected $species;
    /**
     * @var Animal[] the population
     */
    protected $animals;
    /**
     * @var PopulationData data log for the population
     */
    protected $stats;

    /**
     * Simulated Population.
     *
     * @param Habitat $habitat
     * @param Species $species
     *
     * @return Population
     */
    public function __construct(Habitat $habitat, Species $species)
    {
        $this->habitat = $habitat;
        $this->species = $species;
        $this->animals = array();
        $this->animals[] = new Animal($species, Animal::MALE);
        $this->animals[] = new Animal($species, Animal::FEMALE);
        $this->stats = new PopulationData($habitat, $species);
        $this->stats->incrementLived(2);
    }

    /**
     * Gets the habitat name.
     *
     * @return string habitat's name
     */
    public function getHabitatName() : string
    {
        return $this->habitat->getName();
    }

    /**
     * Gets the species name.
     *
     * @return string species' name
     */
    public function getSpeciesName() : string
    {
        return $this->species->getName();
    }

    /**
     * If the population has a mature male.
     *
     * @return bool true if the population has a mature male, false otherwise
     */
    protected function hasViableMale() : bool
    {
        foreach ($this->animals as $animal) {
            if ($animal->isMale() && $animal->isMature()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the set of mature males.
     *
     * @return Animal[] all mature males
     */
    protected function getViableMales() : array
    {
        return array_filter(
            $this->animals,
            function (Animal $animal) {
                return ($animal->isMale() && $animal->isMature());
            }
        );
    }

    /**
     * Gets the population of animals.
     *
     * @return Animals[] the population
     */
    public function getPopulation() : array
    {
        return $this->animals;
    }

    /**
     * Gets the population's size.
     *
     * @return int population size
     */
    public function getPopulationSize() : int
    {
        return count($this->animals);
    }

    /**
     * Gets the population data.
     *
     * @return PopulationData population's log data
     */
    public function getPopulationStats() : PopulationStats
    {
        return $this->stats;
    }

    /**
     * Simulates a population for a given month.
     *
     * @param int $month month to simulate
     *
     * @return PopulationData population's log data
     */
    public function simulate(int $month) : PopulationData
    {
        $this->stats->incrementLength();

        $environment = new Environment($this->habitat, $month);
        $survived = array();

        shuffle($this->animals);

        while ($animal = array_pop($this->animals)) {
            try {
                $survived[] = $animal->survive($environment);
            } catch (Overheated $e) {
                //they all overheat
                $deaths = count($this->animals) + 1;
                $this->stats->incrementDeaths($deaths)
                            ->incrementOverheated($deaths);
                $this->animals = array();
            } catch (Froze $e) {
                //they all freeze
                $deaths = count($this->animals) + 1;
                $this->stats->incrementDeaths($deaths)
                            ->incrementFroze($deaths);
                $this->animals = array();
            } catch (NaturalCauses $e) {
                $this->stats->incrementDeaths(1)
                            ->incrementNaturalCauses(1);
            } catch (StarvedException $e) {
                $this->stats->incrementDeaths(1)
                            ->incrementStarvation(1);
            } catch (ThirstedException $e) {
                $deaths = count($this->animals) + 1;
                $this->stats->incrementDeaths($deaths)
                            ->incrementDehydrated($deaths);
                $this->animals = array();
            }

            //the animal survived, check reproduction
            switch (true) {
            case $animal->isMale():
                break;

            case $animal->isPregnant():
                $offspring = $animal->gestate();
                if ($offspring !== null) {
                    $survived[] = $offspring;
                    $this->stats->incrementLived();
                }
                break;

            case ($animal->isMature() && $this->hasViableMale()):
                $animal->copulate($environment);
            }
        }

        $this->animals = $survived;

        $population_size = count($this->animals);
        $this->stats->incrementAggregatePopulation($population_size)
                    ->setMaxPopulation($population_size);

        return $this->stats;
    }
}
