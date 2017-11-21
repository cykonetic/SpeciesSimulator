<?php
namespace cykonetic\SpeciesSimulator\Helper;

use cykonetic\SpeciesSimulator\Habitat;
use cykonetic\SpeciesSimulator\Species;

class PopulationData
{
    protected $habitat;
    protected $species;
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

    public function getHabitatName() : string
    {
        return $this->habitat->getName();
    }

    public function getSpeciesName() : string
    {
        return $this->species->getName();
    }

    public function getLength() : int
    {
        return $this->raw_data['months'];
    }

    public function incrementLength(int $months = 1) : self
    {
        $this->raw_data['months'] += $months;
    }

    public function getLived() : int
    {
        return $this->raw_data['lived'];
    }

    public function incrementLived(int $lives) : self
    {
        $this->raw_data['lived'] += $lives;

        return $this;
    }

    public function getDied() : int
    {
        return $this->raw_data['died'];
    }

    public function incrementDied(int $deaths) : self
    {
        $this->raw_data['died'] += $deaths;

        return $this;
    }

    public function getMortalityRate() : float
    {
        return ($this->raw_data['died'] / $this->raw_data['lived']) * 100;
    }

    public function getMaxPopulation() : int
    {
        return $this->raw_data['max_population'];
    }
    
    public function setMaxPopulation(int $population_size) : self
    {
        if ($this->raw_data['max_population'] < $population_size) {
            $this->raw_data['max_population'] = $population_size;
        }

        return $this;
    }

    public function getAggeregatePopulaation() : int
    {
        return $this->raw_data['agg_population'];
    }

    public function incrementAggregatePopulation(int $population_size) : self
    {
        $this->raw_data['agg_population'] += $population_size;

        return $this;
    }

    public function getAveragePopulation() : float
    {
        return $this->raw_data['agg_population'] / $this->raw_data['months'];
    }

    public function getStarved() : int
    {
        return $this->raw_data['starvation'];
    }

    public function incrementStarved(int $deaths) : self
    {
        $this->raw_data['starvation'] += $deaths;

        return $this;
    }

    public function getStarvationRate() : float
    {
        return ($this->raw_data['starvation'] / $this->died) * 100;
    }

    public function getDehydrated() : int
    {
        return $this->raw_data['thirst'];
    }

    public function incrementDehydrated(int $deaths) : self
    {
        $this->raw_data['thirst'] += $deaths;

        return $this;
    }

    public function getDehydrationRate() : float
    {
        return ($this->raw_data['thirst'] / $this->raw_data['died']) * 100;
    }

    public function getNaturalCauses() : int
    {
        return $this->raw_data['age'];
    }

    public function incrementNaturalCauses(int $deaths) : self
    {
        $this->raw_data['age'] += $deaths;

        return $this;
    }

    public function getNaturalCausesRate() : float
    {
        return ($this->raw_data['age'] / $this->raw_data['died']) * 100;
    }

    public function getFroze() : int
    {
        return $this->raw_data['cold_weather'];
    }

    public function incrementFroze(int $deaths) : self
    {
        $this->raw_data['cold_weather'] += $deaths;

        return $this;
    }

    public function getFrozeRate() : float
    {
        return ($this->raw_data['cold_weather'] / $this->raw_data['died']) * 100;
    }

    public function getOverHeated() : int
    {
        return $this->raw_data['hot_weather'];
    }

    public function incrementOverHeated(int $deaths) : self
    {
        $this->raw_data['hot_weather'] += $deaths;

        return $this;
    }

    public function getOverHeatedRate() : float
    {
        return ($this->raw_data['hot_weather'] / $this->raw_data['died']) * 100;
    }

    public function getRawData() : array
    {
        return $this->raw_data;
    }

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

    public function render()
    {
        echo "$this->species_name\n";
        echo "\t$this->habitat_name\n";
        echo "\t\tAverage Population: ".sprintf('%01.1f', $this->getAveragePopulation())."\n";
        echo "\t\tMax Population: ".$this->raw_stats['max_population']."\n";
        echo "\t\tMoratality Rate: ".sprintf('%01.2f%%', $this->getMortalityRate())."\n";
        echo "\t\tCause of Death:\n";
        echo "\t\t\tstarvation: ".sprintf('%01.2f%%', $this->getStarvationRate())."\n";
        echo "\t\t\tthirst: ".sprintf('%01.2f%%', $this->getDehydrationRate())."\n";
        echo "\t\t\tage: ".sprintf('%01.2f%%', $this->getNaturalCausesRate())."\n";
        echo "\t\t\tcold_weather: ".sprintf('%01.2f%%', $this->getFrozeRate())."\n";
        echo "\t\t\thot_weather: ".sprintf('%01.2f%%', $this->getOverHeatedRate())."\n";
    }
}
