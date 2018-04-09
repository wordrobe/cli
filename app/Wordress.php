<?php

namespace Wordress;

use Symfony\Component\Console\Application;
use Wordress\Command\SetupCommand;
use Wordress\Command\CreateCommand;

/**
 * Class Wordress
 * @package Wordress
 */
class Wordress
{
	private static $console;

	public static function init() {
		if (!self::$console) {
			self::$console = new Application();
			self::$console->add(new SetupCommand());
			self::$console->add(new CreateCommand());
			self::$console->run();
		}
	}
}