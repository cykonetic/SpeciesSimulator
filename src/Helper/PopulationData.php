<?php
namespace cykonetic\SpeciesSimulator\Helper;

#use cykonetic\SpeciesSimulator\{Habitat, Species};

/**
 * statistical data regarding a population
 */
class PopulationData
{

    /**
     *
     * @var string name of habitat
     */
    protected $habitat;

    /**
     *
     * @var string name of species
     */
    protected $species;

    /**
     *
     * @var array raw population stats
     */
    protected $raw_data;

    /**
     *
     * @param Habitat $habitat
     * @param Species $species
     */
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
     * sets raw stat value
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->raw_stats[$name] = $value;
    }

    /**
     * returns raw stat value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->raw_stats)) {
            $trace = debug_backtrace();
            $message = sprintf('Undefined property via __get(): in %s on line %s', $name, $trace[0]['file'], $trace[0]['line']);
            trigger_error($message, E_USER_ERROR);
        }
            
        return $this->raw_stats[$name];
    }

    /**
     * getter for habitat name
     *
     * @return string
     */
    public function getHabitatName() : string
    {
        return $this->habitat->getName();
    }

    /**
     * getter for soecies name
     *
     * @return string
     */
    public function getSpeciesName() : string
    {
        return $this->species->getName();
    }

    /**
     * getter for avarage population
     *
     * @return float
     */
    public function getAveragePopulation() : float
    {
        return $this->agg_population / $this->months;
    }

    /**
     * getter for mortality rate (%)
     *
     * @return float
     */
    public function getMortalityRate() : float
    {
        return ($this->died / $this->lived) * 100;
    }

    /**
     * getter for starvation rate (%)
     *
     * @return float
     */
    public function getStarvationRate() : float
    {
        return ($this->starvation / $this->died) * 100;
    }

    /**
     * getter for dehydration rate (%)
     *
     * @return float
     */
    public function getThirstRate() : float
    {
        return ($this->thirst / $this->died) * 100;
    }

    /**
     * getter for old age rate (%)
     *
     * @return float
     */
    public function getAgeRate() : float
    {
        return ($this->age / $this->died) * 100;
    }

    /**
     * getter for frozen rate (%)
     *
     * @return float
     */
    public function getFrozenRate() : float
    {
        return ($this->cold_weather / $this->died) * 100;
    }

    /**
     * getter for burned rate (%)
     *
     * @return float
     */
    public function getBurnedRate() : float
    {
        return ($this->hot_weather / $this->died) * 100;
    }

    /**
     * combine another PopulationStat with this
     *
     * @param \PopulationStats $mergingStats
     */
    public function merge(PopulationData $mergeData)
    {
        foreach (array_keys($this->raw_stats) as $key) {
            switch ($key) {
                case 'max_population':
                    if ($this->raw_data[$key] < $mergeData->$key) {
                        $this->raw_data[$key] = $mergeData->$key;
                    }
                    break;
                default:
                    $this->raw_data[$key] += $mergeData->$key;
            }
        }
    }

    /**
     * Render stats to screen
     */
    public function render()
    {
        echo "$this->species_name\n";
        echo "\t$this->habitat_name\n";
        echo "\t\tAverage Population: ".sprintf('%01.1f', $this->getAveragePopulation())."\n";
        echo "\t\tMax Population: ".$this->raw_stats['max_population']."\n";
        echo "\t\tMoratality Rate: ".sprintf('%01.2f%%', $this->getMortalityRate())."\n";
        echo "\t\tCause of Death:\n";
        echo "\t\t\tstarvation: ".sprintf('%01.2f%%', $this->getStarvationRate())."\n";
        echo "\t\t\tthirst: ".sprintf('%01.2f%%', $this->getThirstRate())."\n";
        echo "\t\t\tage: ".sprintf('%01.2f%%', $this->getAgeRate())."\n";
        echo "\t\t\tcold_weather: ".sprintf('%01.2f%%', $this->getFrozenRate())."\n";
        echo "\t\t\thot_weather: ".sprintf('%01.2f%%', $this->getBurnedRate())."\n";
    }
}
