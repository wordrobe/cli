<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\InitCommand;
use Wordrobe\Command\AddCommand;
use Composer\Factory;

define('PROJECT_ROOT', dirname(Factory::getComposerFile()));
define('TEMPLATES_MODELS_PATH', __DIR__ . '/TemplateModels');
define('BOILERPLATES_PATH', __DIR__ . '/ThemeBoilerplates');

$wordrobe = new Application('Wordrobe', 'v1.0.0');
$wordrobe->add(new InitCommand());
$wordrobe->add(new AddCommand());
$wordrobe->run();
