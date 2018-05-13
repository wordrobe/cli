<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\BoilerplateManager;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\StyleBuilder;

/**
 * Class SetupCommand
 * @package Wordrobe\Command
 */
class SetupCommand extends BaseCommand
{
	protected function configure()
    {
        $this->setName('setup');
        $this->setDescription('A setup wizard for your brand new Wordrobe project configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		parent::execute($input, $output);

		// CHECKING PREVIOUS SETUP
		if (Config::read()) {
			Dialog::write('Your project is already configured', 'green');
		} else {
			$this->buildConfigTemplate();
			Config::read();
			// SETTING THEMES DIRECTORY PERMISSIONS
			FilesManager::setPermissions(Config::get('project_root') . '/' . Config::get('themes_path'), 0755);
		}
    }

	/**
	 * Handles config template building
	 */
	private function buildConfigTemplate()
	{
		return new ConfigBuilder();
	}
}
