<?php
namespace cykonetic\SpeciesSimulator;

use cykonetic\SpeciesSimulator\Exception\Dehydrated;
use cykonetic\SpeciesSimulator\Exception\Froze;
use cykonetic\SpeciesSimulator\Exception\NaturalCauses;
use cykonetic\SpeciesSimulator\Exception\Overheated;
use cykonetic\SpeciesSimulator\Exception\Starved;

class Animal
{
    const MALE   = 'male';
    const FEMALE = 'female';

    protected $species;
    protected $gender;
    protected $age = 0;
    protected $hunger = 0;
    protected $gestation = 0;
    
    public function __construct(Species $species, string $gender = 'unknown')
    {
        $this->species = $species;

        if (!in_array($gender, array(self::FEMALE, self::MALE))) {
            $gender = rand(0, 1) ? self::FEMALE : self::MALE;
        }

        $this->gender = $gender;
    }

    public function isMale() : bool
    {
        return (self::MALE === $this->gender);
    }

    public function isFemale() : bool
    {
        return (self::FEMALE === $this->gender);
    }

    public function isMature() : bool
    {
        return ($this->species->getMinimumBreeding() * 12 <= $this->age)
            && ($this->species->getMaximumBreeding() * 12 >= $this->age);
    }

    public function isPregnant() : bool
    {
        return $this->gestation > 0;
    }

    protected function eat(Environment $environment) : self
    {
        $this->hunger += 1;

        if ($environment->provideFood($this->species->getRequiredFood())) {
            $this->hunger = 0;
        } elseif (2 < $this->hunger) {
            throw new Starved();
        }
        
        return $this;
    }

    protected function drink(Environment $environment) : self
    {
        if (!$environment->provideWater($this->species->getRequiredWater())) {
            throw new Dehydrated();
        }

        return $this;
    }

    protected function age() : self
    {
        $this->age += 1;

        if ($this->species->getMaximumAge() * 12 < $this->age) {
            throw new NaturalCauses();
        }

        return $this;
    }

    protected function tolerate(Environment $environment) : self
    {
        if ($environment->getTemperature() > $this->species->getMaximumTolerance()) {
            throw new Overheated();
        } elseif ($environment->getTemperature() < $this->species->getMinimumTolerance()) {
            throw new Froze();
        }

        return $this;
    }

    public function copulate(Environment $environment) : self
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
        
        return $this;
    }

    public function gestate() : ?Animal
    {
        $this->gestation += 1;

        if ($this->species->getGestationPeriod() < $this->gestation) {
            return $this->birth();
        }

        return null;
    }

    private function birth() : Animal
    {
        $this->gestation = 0;

        return new Animal($this->species);
    }

    public function survive(Environment $environment) : self
    {
        $activities = array('age','drink','eat','tolerate');

        shuffle($activities);

        foreach ($activities as $doIt) {
            if ('age' === $doIt) {
                $this->age();
            } else {
                #$this->$doIt($environment);
                call_user_func(array($this, $doIt), $environment);
            }
        }

        return $this;
    }
}
