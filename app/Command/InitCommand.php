<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
		$runThemeCreation = Dialog::getConfirmation("Your project doesn't have any themes yet. Do you want to create one right now?", true, 'yellow');
		if ($runThemeCreation) {
			$command = $this->getApplication()->find('add');
			$arguments = ['command' => 'add', 'feature' => 'theme'];
			$command->run(new ArrayInput($arguments), Dialog::$output);
		}
	}
}
