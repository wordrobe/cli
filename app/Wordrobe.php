<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\SetupCommand;
use Wordrobe\Command\AddThemeCommand;
use Wordrobe\Command\AddTemplateCommand;

define('PROJECT_ROOT', realpath(__DIR__ . '/../../../../'));
define('TEMPLATES_MODELS_PATH', __DIR__ . '/TemplateModels');
define('BOILERPLATES_PATH', __DIR__ . '/ThemeBoilerplates');

$wordrobe = new Application('Wordrobe', 'v1.0.0');
$wordrobe->add(new SetupCommand());
$wordrobe->add(new AddThemeCommand());
$wordrobe->add(new AddTemplateCommand());
$wordrobe->run();
