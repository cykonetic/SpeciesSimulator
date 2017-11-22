
<?php
use cykonetic\SpeciesSimulator\Simulator;
use cykonetic\SpeciesSimulator\Helper\Configuration;

$config = Configuration::buildFromYamlFile('config.yml');
$simulation = new Simulator($config);
$simulation->getSummarySimulationStats()->render();
