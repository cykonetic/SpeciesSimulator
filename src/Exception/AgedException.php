<?php
/**
 *
 */
namespace cykonetic\SpeciesSimulator\Exception;

use Exception;
/**
 *
 */
class AgedException extends Exception
{

    function __construct() 
    {
        parent::__construct('too old');
    }

}
