<?php
namespace cykonetic\SpeciesSimulator\Exception;

use Exception;
/**
 * Exception thrown when an animal dies from low temperature
 */
class FrozeException extends Exception
{

    function __construct() 
    {
        parent::__construct('too cold');
    }

}
