<?php
/**
 * SpeciesSimulator/Exception/Froze.php
 *
 * @package   cykonetic\SpeciesSimulator
 * @link      https://github.com/cykonetic/species-simulator
 * @author    Nicholai Bush <nicholaibush@yahoo.com>
 * @copyright Copyright (c) 2017 Nicholai Bush <nicholaibush@yahoo.com>
 * @license   https://raw.githubusercontent.com/cykonetic/species-simulator/master/MIT.license.txtdataProvider
 */
namespace cykonetic\SpeciesSimulator\Exception;

use Exception;

/**
 * Exception thrown when an animal dies from low temperature
 */
class Froze extends Exception
{
    /**
     * Froze Exception
     *
     * @return Exception
     */
    public function __construct()
    {
        parent::__construct('too cold');
    }
}
