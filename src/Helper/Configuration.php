<?php
declare(strict_types=1);

namespace cykonetic\SpeciesSimulator\Helper;

use Exception;
use Symfony\Component\Yaml\Yaml;
use cykonetic\SpeciesSimulator\{Habitat,Species};

class Configuration
{

    public static function BuildFromConfigArray(array $config_array) : Configuration
    {
        if (!(isset($config_array['habitats']) and is_array($config_array['habitats']) and count($config_array['habitats']))) {
            throw new Exception('Required key `habitats` is empty or not set.');
        }
        elseif (!(isset($config_array['species']) and is_array($config_array['species']) and count($config_array['species']))) {
            throw new Exception('Required key `species` is empty or not set.');
        }

        $habitats = array();
        foreach ($config_array['habitats'] as $habitat_config) {
            $name = $monthly_food = $monthly_water = $average_temperature = null;
            extract($habitat_config, EXTR_IF_EXISTS);
            $habitats[] = new Habitat($name, $monthly_food, $monthly_water, $average_temperature);
        }

        $species = array();
        foreach ($config_array['species'] as $species_config) {
            $name = $attributes = null;
            extract($species_config, EXTR_IF_EXISTS);
            $species[] = new Species($name, $attributes);
        }

        $years = 100;
        if (isset($config_array['years']) and is_numeric($config_array['years'])) {
            $years = intval($config_array['years']);
        }

        $iterations = 10;
        if (isset($config_array['iterations']) and is_numeric($config_array['iterations'])) {
            $iterations = intval($config_array['iterations']);
        }

        return new Configuration($habitats, $species, $years, $iterations);
    }

    public static function BuildFromYamlFile(string $config_yaml) : Configuration
    {
        if (!file_exists($config_yaml)) {
            throw new Exception("Unable to open `$config_yaml`");
        }
        return self::BuildFromArray(Yaml::parse(file_get_contents($config_yaml)));
    }

    protected $habitats;
    protected $species;
    protected $length;
    protected $iterations;
    
    public function __construct(array $habitats, array $species, int $years = 100, int $iterations = 10)
    {
        $this->habitats = $habitats;
        $this->species = $species;
        $this->length = $years * 12;
        $this->iterations = $iterations;
    }
    
    public function getHabitats() : array
    {
        return $this->habitats;
    }
    
    public function getSpecies() : array
    {
        return $this->species;
    }
    
    public function getLength() : int
    {
        return $this->length;
    }
    
    public function getIterations() : int
    {
        return $this->iterations;
    }
}
