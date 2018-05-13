<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
abstract class AddCommand extends BaseCommand
{
	/**
	 * Checks config existence and starts setup if missing
	 */
	protected function checkSetup()
	{
		if (!Config::read()) {
			$runSetup = Dialog::getConfirmation('Attention: your project is not configured. Do you want to run setup?', true);
			if ($runSetup) {
				$command = $this->getApplication()->find('setup');
				$arguments = ['command' => 'setup'];
				$command->run(new ArrayInput($arguments), Dialog::$output);
			}
			exit();
		}
	}

	/**
	 * Template builder getter
	 *
	 * @param $name
	 * @return string
	 */
	protected function getBuilder($name)
	{
		$builder = 'Wordrobe\Builder\\' . $name . 'Builder';
		if (!class_exists($builder)) {
			Dialog::write('Error: ' . $builder . ' is not defined.', 'red');
			exit();
		}
		return $builder;
	}
}
