<?php
/**
 * SpeciesSimulator/Environment.php
 *
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
declare(strict_types=1);

namespace cykonetic\SpeciesSimulator\Helper;

use Exception;
use Symfony\Component\Yaml\Yaml;
use cykonetic\SpeciesSimulator\Habitat;
use cykonetic\SpeciesSimulator\Species;

/**
 * Configuration used to setup Simulator
 */
class Configuration
{
    /**
     * Builds a Cunfiguration given a parameter array.
     *
     * @param array   $config_array array of parameters parsed from yaml,or built programatically
     *
     * @throws Exception if 'habitats' key is not defined
     * @throws Exception if 'species' key is not defined
     *
     * @return Configuration
     */
    public static function buildFromConfigArray(array $config_array) : Configuration
    {
        if (!(isset($config_array['habitats']) and is_array($config_array['habitats']) and count($config_array['habitats']))) {
            throw new Exception('Required key `habitats` is empty or not set.');
        } elseif (!(isset($config_array['species']) and is_array($config_array['species']) and count($config_array['species']))) {
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

    /**
     * Builds a Cunfiguration given a YAML configuration file name
     *
     * @param string  $config_yaml YAML file name
     *
     * @throws Exception if $config_yaml is not a findable file name
     *
     * @return Configuration
     */
    public static function buildFromYamlFile(string $config_yaml) : Configuration
    {
        if (!file_exists($config_yaml)) {
            throw new Exception("Unable to open `$config_yaml`");
        }
        return self::buildFromArray(Yaml::parse(file_get_contents($config_yaml)));
    }

    /**
     * @var Habitat[] Habitats to simulate
     */
    protected $habitats;
    /**
     * @var Species[] Species to simulate
     */
    protected $species;
    /**
     * @var int months number of months to run each simulation
     */
    protected $length;
    /**
     * @var int number of times to repeat each simulation
     */
    protected $iterations;

    public function __construct(array $habitats, array $species, int $years = 100, int $iterations = 10)
    {
        $this->habitats = $habitats;
        $this->species = $species;
        $this->length = $years * 12;
        $this->iterations = $iterations;
    }

    /**
     * Gets the habitats to simulate
     *
     * @return Habitat[] habitats to simulate
     */
    public function getHabitats() : array
    {
        return $this->habitats;
    }

    /**
     * Gets the dpecies to simulate
     *
     * @return Species[] species to simulate
     */
    public function getSpecies() : array
    {
        return $this->species;
    }

    /**
     * Gets the length of simulation (in months)
     *
     * @return int months of simulation
     */
    public function getLength() : int
    {
        return $this->length;
    }

    /**
     * Gets the number of repititions for simulations
     *
     * @return int repititions of simulation
     */
    public function getIterations() : int
    {
        return $this->iterations;
    }
}
