<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\SetupCommand;
use Wordrobe\Command\AddCommand;

//define('PROJECT_ROOT', realpath(__DIR__ . '/../../../../'));
define('PROJECT_ROOT', '/Users/Andrea/Progetti/wordrobe-test');
define('TEMPLATES_PATH', __DIR__ . '/Templates');
define('BOILERPLATES_PATH', __DIR__ . '/ThemeBoilerplate');

$wordrobe = new Application('Wordrobe', 'v1.0.0');
$wordrobe->add(new SetupCommand());
$wordrobe->add(new AddCommand());
$wordrobe->run();
