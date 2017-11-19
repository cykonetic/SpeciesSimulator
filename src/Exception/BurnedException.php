<?php
namespace cykonetic\SpeciesSimulator\Exception;

use Exception;

/**
 * Exception thrown when an animal dies from high temperature
 */
class BurnedException extends Exception
{
    public function __construct()
    {
        parent::__construct('too hot');
    }
}
