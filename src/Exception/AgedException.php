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
    public function __construct()
    {
        parent::__construct('too old');
    }
}
