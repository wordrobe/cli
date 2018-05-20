<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Config;
use Wordrobe\Factory\ConfigFactory;
use Wordrobe\Helper\Dialog;

/**
 * Class InitCommand
 * @package Wordrobe\Command
 */
class InitCommand extends BaseCommand
{
	protected function configure()
	{
		$this->setName('init');
		$this->setDescription('Initializes Wordrobe');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);
		ConfigFactory::startWizard();
	}
}
