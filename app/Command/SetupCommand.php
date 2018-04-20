<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\BoilerplateManager;
use Wordrobe\TemplateBuilder\ConfigTemplateBuilder;
use Wordrobe\TemplateBuilder\StyleTemplateBuilder;

/**
 * Class SetupCommand
 * @package Wordrobe\Command
 */
class SetupCommand extends BaseCommand
{
	protected function configure()
    {
        $this->setName('setup');
        $this->setDescription('A setup wizard for your brand new Wordrobe based project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		parent::execute($input, $output);
		$firstTimeSetup = true;

        // SETTING ROOT DIRECTORY PERMISSIONS
		FilesManager::setPermissions(PROJECT_ROOT, 0755);

		// CHECKING PREVIOUS SETUP
		if (Config::get()) {
			$firstTimeSetup = false;
		} else {
			$this->buildConfigTemplate();
			Config::read();
		}

		// SETTING THEMES DIRECTORY PERMISSIONS
		FilesManager::setPermissions(PROJECT_ROOT . '/' . Config::get('themes_path'), 0755);

		// ADDING THEME FILES
        if ($firstTimeSetup) {
			// COPYING THEME BOILERPLATE FILES
			BoilerplateManager::copyFiles();

			// CREATING style.css
            $this->buildStyleTemplate();
        }
    }

	/**
	 * Handles config template building
	 */
	private function buildConfigTemplate()
	{
		return new ConfigTemplateBuilder();
	}

	/**
	 * Handles style template building
	 */
	private function buildStyleTemplate()
	{
		return new StyleTemplateBuilder();
	}
}
