<?php
namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Helper\PopulationStats;


class Simulator
{

    protected $habitats;
    protected $species;
    protected $length;
    protected $populations = array();
    protected $ran = false;
    protected $ready = false;

    public function __construct($config_yaml = null)
    {
        if ($config_yaml !== null) {
        }
        //array $habitats, array $species, $years
        $this->habitats = $habitats;
        $this->species  = $species;
        $this->length   = $years*12;
    }

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
