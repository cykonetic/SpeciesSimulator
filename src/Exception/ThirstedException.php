<?php
namespace cykonetic\SpeciesSimulator\Exception;

use Exception;
/**
 * Exception thrown when an animal dies from thirst
 */
class ThirstedException extends Exception
{

    function __construct() 
    {
        parent::__construct('not enough water');
    }

}
