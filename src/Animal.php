<?php
/**
 * Animal.php.
 *
 * Animal is a creature of type Species
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
 * @package   DatalotSim
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright 2017 Nickolai Bush
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   0.0.1
 * @see \DatalotSim\Species
 * @since     0.0.1
 */

/**
 * DatalotSim.
 */
namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Exception\AgedException;
use cykonetic\SpeciesSimulator\Exception\BurnedException;
use cykonetic\SpeciesSimulator\Exception\FrozeException;
use cykonetic\SpeciesSimulator\Exception\StarvedException;
use cykonetic\SpeciesSimulator\Exception\ThirstedException;

// {{{ Animal

/**
 * Animal
 *
 * Simulates an indiviual within a Population
 */
class Animal
{

    // {{{ constants
    
    /**
     * Definition of male.
     */
    const MALE   = 'male';
    /**
     * Definition of female.
     */
    const FEMALE = 'female';
    
    // }}}
    // {{{ properties
    
    /**
     * The instance's species.
     *
     * @var Species animal type
     */
    protected $species;
    /**
     * The instance's gender.
     *
     * @var string animal gender (male|female)
     */
    protected $gender;
    /**
     * The instance's age.
     *
     * @var int
     */
    protected $age = 0;
    /**
     * The instance's current level of hunger.
     *
     * @var int current hunger level
     */
    protected $hunger = 0;
    /**
     * If not pregnant this value is 0, otherwise the month count of pregnancy
     *
     * @var int gestation state
     */
    protected $gestation = 0;
    
    //}}}

    /**
     * Animal Constructor.
     *
     * @param \DatalotSim\Species $species Type of animal
     * @param string $gender Male| Female| null(randomly decide)
     *
     */
    public function __construct(Species $species, $gender = null)
    {
        $this->species = $species;

        if (!$gender) {
            $gender = rand(0, 1)?self::MALE:self::FEMALE;
        }

        $this->gender = $gender;
    }

    /**
     * Returns true if the instance is male, otherwise false.
     *
     * @return boolean
     */
    public function isMale()
    {
        return (self::MALE == $this->gender);
    }

    /**
     * Returns true if the instance is female, otherwise false.
     *
     * @return boolean
     */
    public function isFemale()
    {
        return (self::FEMALE == $this->gender);
    }

    /**
     * Returns true if the instance is an adult, otherwise false.
     *
     * @return boolean
     */
    public function isMature()
    {
        return (
         ($this->species->getMinimumBreeding()*12) <= $this->age &&
         ($this->species->getMaximumBreeding()*12) >= $this->age
        );
    }

    /**
     * Returns true if the instance is pregnant, otherwise false.
     *
     * @return boolean
     */
    public function isPregnant()
    {
        return $this->gestation > 0;
    }

    /**
     * Determines if an animal eats or dies.
     *
     * @param \DatalotSim\Environment $environment The envronment.
     *
     * @throws \DatalotSim\Exceptions\StarvedException
     */
    protected function eat(Environment $environment)
    {
        $this->hunger += 1;

        if ($environment->consumedFood($this->species)) {
            $this->hunger = 0;
        } elseif (2 < $this->hunger) {
            throw new StarvedException();
        }
    }

    /**
     * Determines if an animal drinks or dies.
     *
     * @param  \DatalotSim\Environment $environment The envronment.
     *
     * @throws \DatalotSim\Exceptions\ThirstedException
     */
    protected function drink(Environment $environment)
    {
        if (!$environment->consumedWater($this->species)) {
            throw new ThirstedException();
        }
    }

    /**
     * Determines if an animal gets older or dies.
     *
     * @throws \DatalotSim\Exceptions\AgedException
     */
    protected function age()
    {
        $this->age += 1;

        if (($this->species->getMaximumAge()*12) < $this->age) {
            throw new AgedException();
        }
    }

    /**
     * Determines if an animal survives the environments temperature or dies.
     *
     * @param  \DatalotSim\Environment $environment The envronment.
     *
     * @throws \DatalotSim\Exceptions\BurnedException
     * @throws \DatalotSim\Exceptions\FrozeException
     */
    protected function weather(Environment $environment)
    {
        if ($environment->getTemperature() > $this->species->getMaximumTolerance()) {
            throw new BurnedException();
        } elseif ($environment->getTemperature() < $this->species->getMinimumTolerance()) {
            throw new FrozeException();
        }
    }

    /**
     * Determines the outcome of mating.
     *
     * @param  \DatalotSim\Environment $environment The envronment.
     */
    public function copulate(Environment $environment)
    {
        if ($this->isFemale()
            && $this->isMature()
            && !$this->isPregnant()
            && ((rand(1, 200) == 1)
            || ((0 < $environment->getFood())
            && (0 < $environment->getWater())))
        ) {
            $this->gestate();
        }
    }

    /**
     * Determines the outcome of gestation.
     *
     * @return \DatalotSim\Animal|null
     */
    public function gestate()
    {
        $this->gestation += 1;

        if ($this->species->getGestationPeriod() < $this->gestation) {
            return $this->birth();
        }

        return null;
    }

    /**
     * Creates an offspring for this animal.
     *
     * @return \DatalotSim\Animal
     */
    private function birth()
    {
        $this->gestation = 0;

        return new Animal($this->species);
    }

    /**
     * Determines survival for this animal in the given environment.
     *
     * The animal must weather the temperature, eat, drink, and get older.
     * These will be randomized for each creature each month.
     *
     * @param \DatalotSim\Environment $environment The envronment.
     */
    public function survive(Environment $environment)
    {
        $toSurvive = array('weather', 'eat', 'drink', 'age');

        shuffle($toSurvive);

        foreach ($toSurvive as $do) {
            $this->$do($environment);
        }
    }
}

// }}}
