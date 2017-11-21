<?php
namespace cykonetic\SpeciesSimulator;

#use cykonetic\SpeciesSimulator\{Animal,Environment,Habitat,Species};
use cykonetic\SpeciesSimulator\Exception\Dehydrated;
use cykonetic\SpeciesSimulator\Exception\Froze;
use cykonetic\SpeciesSimulator\Exception\NaturalCauses;
use cykonetic\SpeciesSimulator\Exception\Overheated;
use cykonetic\SpeciesSimulator\Exception\Starved;
use cykonetic\SpeciesSimulator\Helper\PopulationData;

class Population
{
    protected $habitat;
    protected $species;
    protected $animals;
    protected $stats;

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

    public function getHabitatName() : string
    {
        return $this->habitat->getName();
    }

    public function getSpeciesName() : string
    {
        return $this->species->getName();
    }

    public function simulate($month)
    {
        $this->stats->incrementLength();

        $environment = new Environment($this->habitat, $month);

        $this->survive($environment)->breed($environment);

        $population_size = count($this->animals);
        $this->stats->incrementAggregatePopulation($population_size)
                    ->setMaxPopulation($population_size);
    }

    protected function survive(Environment $environment) : self
    {
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
        }

        $this->animals = $survived;

        return $this;
    }

    protected function breed(Environment $environment) : self
    {
        $newGeneration = array();

        foreach ($this->animals as $animal) {
            switch (true) {
            case $animal->isMale():
                break;

            case $animal->isPregnant():
                $offspring = $animal->gestate();
                if ($offspring !== null) {
                    $newGeneration[] = $offspring;
                }
                break;

            case ($animal->isMature() && $this->hasViableMale()):
                $animal->copulate($environment);
                break;

            }
        }

        if (count($newGeneration)) {
            $this->stats->lived += count($newGeneration);
            $this->animals = array_merge($this->animals, $newGeneration);
        }

        return $this;
    }

    protected function hasViableMale() : bool
    {
        foreach ($this->animals as $animal) {
            if ($animal->isMale() && $animal->isMature()) {
                return true;
            }
        }
        return false;
    }

    protected function getViableMales() : array
    {
        return array_filter(
            $this->animals,
            function (Animal $animal) {
                return ($animal->isMale() && $animal->isMature());
            }
        );
    }

    public function getPopulationStats() : PopulationStats
    {
        return $this->stats;
    }
}
