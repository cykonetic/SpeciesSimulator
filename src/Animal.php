<?php
namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Exception\{AgedException,BurnedException,FrozeException,StarvedException,ThirstedException};

class Animal
{

    const MALE   = 'male';
    const FEMALE = 'female';
    
    protected $species;
    protected $gender;
    protected $age = 0;
    protected $hunger = 0;
    protected $gestation = 0;
    
    public function __construct(Species $species, $gender = null)
    {
        $this->species = $species;

        if (!$gender) {
            $gender = rand(0, 1)?self::MALE:self::FEMALE;
        }

        $this->gender = $gender;
    }

    public function isMale()
    {
        return (self::MALE == $this->gender);
    }

    public function isFemale()
    {
        return (self::FEMALE == $this->gender);
    }

    public function isMature()
    {
        return (
         ($this->species->getMinimumBreeding()*12) <= $this->age &&
         ($this->species->getMaximumBreeding()*12) >= $this->age
        );
    }

    public function isPregnant()
    {
        return $this->gestation > 0;
    }

    protected function eat(Environment $environment)
    {
        $this->hunger += 1;

        if ($environment->consumedFood($this->species)) {
            $this->hunger = 0;
        } elseif (2 < $this->hunger) {
            throw new StarvedException();
        }
    }

    protected function drink(Environment $environment)
    {
        if (!$environment->consumedWater($this->species)) {
            throw new ThirstedException();
        }
    }

    protected function age()
    {
        $this->age += 1;

        if (($this->species->getMaximumAge()*12) < $this->age) {
            throw new AgedException();
        }
    }

    protected function weather(Environment $environment)
    {
        if ($environment->getTemperature() > $this->species->getMaximumTolerance()) {
            throw new BurnedException();
        } elseif ($environment->getTemperature() < $this->species->getMinimumTolerance()) {
            throw new FrozeException();
        }
    }

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

    public function gestate()
    {
        $this->gestation += 1;

        if ($this->species->getGestationPeriod() < $this->gestation) {
            return $this->birth();
        }

        return null;
    }

    private function birth()
    {
        $this->gestation = 0;

        return new Animal($this->species);
    }

    public function survive(Environment $environment)
    {
        $toSurvive = array('weather', 'eat', 'drink', 'age');

        shuffle($toSurvive);

        foreach ($toSurvive as $do) {
            $this->$do($environment);
        }
    }
}
