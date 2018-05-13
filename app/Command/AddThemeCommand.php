<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class AddThemeCommand
 * @package Wordrobe\Command
 */
class AddThemeCommand extends AddCommand
{
	protected function configure()
	{
		$this->setName('add:theme');
		$this->setDescription('Adds a new theme.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		// CHECKING SETUP
		$this->checkSetup();

		// CREATING TEMPLATE
		$this->buildTheme();
	}

	/**
	 * Handles theme building
	 *
	 * @return mixed
	 */
	private function buildTheme()
	{
		return new ThemeBuilder();
	}

	/**
	 * Handles style template building
	 */
	private function buildStyleTemplate()
	{
		return new StyleBuilder();
	}
}
