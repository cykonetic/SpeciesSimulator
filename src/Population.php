<?php
namespace cykonetic\SpeciesSimulator;

#use cykonetic\SpeciesSimulator\{Animal,Environment,Habitat,Species};
use cykonetic\SpeciesSimulator\Exception\{AgedException,BurnedException,FrozeException,StarvedException,ThirstedException};
use cykonetic\SpeciesSimulator\Helper\{PopulationStats};

/**
 * An instance of a species in a habitat
 */

class Population
{

    /**
     *
     * @var Habitat
     */
    protected $habitat;
    /**
     *
     * @var Species
     */
    protected $species;
    /**
     *
     * @var Animal[] current population
     */
    protected $animals;
    /**
     *
     * @var PopulationStats population stats
     */
    protected $stats;

    /**
     *
     * @param Habitat $habitat given habitat
     * @param Species $species given species
     */
    function __construct(Habitat $habitat, Species $species) 
    {
        $this->habitat      = $habitat;
        $this->species      = $species;
        $this->animals      = array();
        $this->animals[]    = new Animal($this->species, Animal::MALE);
        $this->animals[]    = new Animal($this->species, Animal::FEMALE);
        $this->stats        = new PopulationStats($habitat->getName(), $species->getName());
        $this->stats->lived = 2;
    }

    /**
     *
     * @return string name of habitat
     */
    public function getHabitatName() 
    {
        return $this->habitat->getName();
    }

    /**
     *
     * @return string name of species
     */
    public function getSpeciesName() 
    {
        return $this->species->getName();
    }

    /**
     *
     * @param int $month simulate life for given month
     */
    public function simulate($month) 
    {
        $this->stats->months += 1;
        $environment = new Environment($this->habitat, $month);

        $this->survive($environment);
        $this->breed($environment);

        $this->stats->agg_population += count($this->animals);
        if ($this->stats->max_population < count($this->animals)) {
            $this->stats->max_population = count($this->animals);
        }
    }

    /**
     * check each animals attempt to survive the month
     *
     * @param \Environment $environment
     */
    protected function survive(Environment $environment) 
    {

        $survived = array();
        shuffle($this->animals);

        while ($animal = array_pop($this->animals)) {

            try {
                $animal->survive($environment);
                $survived[] = $animal;

            } catch (BurnedException $e) {
                //they all burn
                $this->stats->died += 1+count($this->animals);
                $this->stats->hot_weather += 1+count($this->animals);
                $this->animals = array();

            } catch (FrozeException $e) {
                //they all freeze
                $this->stats->died += 1+count($this->animals);
                $this->stats->cold_weather += 1+count($this->animals);
                $this->animals = array();

            } catch (AgedException $e) {
                $this->stats->died += 1;
                $this->stats->age += 1;

            } catch (StarvedException $e) {
                $this->stats->died += 1;
                $this->stats->starvation += 1;

            } catch (ThirstedException $e) {
                $this->stats->died += 1+count($this->animals);
                $this->stats->thirst += 1+count($this->animals);
                $this->animals = array();
            }
        }

        $this->animals = $survived;
    }

    /**
     * check the populations breeding and births
     *
     * @param \Environment $environment
     */
    protected function breed(Environment $environment) 
    {

        $new_generation = array();

        foreach ($this->animals as $animal) {
            switch (true) {
            case $animal->isMale():
                break;

            case $animal->isPregnant():
                $offspring = $animal->gestate();
                if ($offspring) {
                    $new_generation[] = $offspring;
                }
                break;

            case ($animal->isMature() && $this->hasViableMale()):
                $animal->copulate($environment);
                break;

            }
        }

        if (count($new_generation)) {
            $this->stats->lived += count($new_generation);
            $this->animals = array_merge($this->animals, $new_generation);
        }
    }

    /**
     * checks the population for a mature male
     *
     * @return boolean
     */
    protected function hasViableMale() 
    {
        foreach ($this->animals as $animal) {
            if ($animal->isMale() && $animal->isMature()) {
                return true;
            }
        }
        return false;
    }

    /**
     * return an array of all mature males
     * (not needed for this project)
     *
     * @return \Animal[]
     */
    protected function getViableMales() 
    {
        return array_filter(
            $this->animals, function ($animal) {
                return ($animal->isMale() && $animal->isMature());
            }
        );
    }

    /**
     * returns vital statistics for this population
     *
     * @return \PopulationStats
     */
    public function getPopulationStats() 
    {
        return $this->stats;
    }

}
