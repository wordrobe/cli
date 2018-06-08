<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\InitCommand;
use Wordrobe\Command\AddCommand;
use Composer\Factory;

define('PROJECT_ROOT', dirname(Factory::getComposerFile()));

$wordrobe = new Application('Wordrobe', 'v1.0.0');
$wordrobe->add(new InitCommand());
$wordrobe->add(new AddCommand());
$wordrobe->run();
