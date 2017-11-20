<?php

namespace cykonetic\SpeciesSimulator\Exception;

use Exception;

class NaturalCauses extends Exception
{
    public function __construct()
    {
        parent::__construct('too old');
    }
}
