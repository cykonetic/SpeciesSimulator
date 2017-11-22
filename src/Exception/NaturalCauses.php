<?php
/**
 * SpeciesSimulator/Exception/NaturalCauses.php
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
 * Summary
 */
class NaturalCauses extends Exception
{
    /**
     * NaturalCauses Exception
     *
     * @return Exception
     */
    public function __construct()
    {
        parent::__construct('too old');
    }
}
