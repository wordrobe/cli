<?php

namespace Wordrobe;

use Symfony\Component\Console\Application;
use Wordrobe\Command\SetupCommand;
use Wordrobe\Command\AddCommand;

// AUTOLOAD
require_once dirname(__DIR__) . '/vendor/autoload.php';

// APPLICATION CONSTANTS
define('PROJECT_ROOT', realpath(__DIR__ . '/../../../../'));
define('TEMPLATES_PATH', __DIR__ . '/Templates');

// APPLICATION COMMANDS
$commands = [new SetupCommand(), new AddCommand()];

// APPLICATION BOOTSTRAP
$console = new Application();
foreach ($commands as $command) {
	$console->add($command);
}
$console->run();
