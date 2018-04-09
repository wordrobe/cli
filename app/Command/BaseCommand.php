<?php

namespace Wordress\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordress\Helper\Dialog;

/**
 * Class BaseCommand
 * @package Wordress\Command
 */
class BaseCommand extends Command
{
	protected static $dialog;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		self::$dialog = new Dialog($this->getHelper('question'), $input, $output);
	}
}
