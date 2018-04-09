<?php

namespace Wordress\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordress\Helper\Config;
use Wordress\Helper\Boilerplate;
use Wordress\TemplateBuilder\ConfigTemplateBuilder;
use Wordress\TemplateBuilder\StyleTemplateBuilder;

/**
 * Class SetupCommand
 * @package Wordress\Command
 */
class SetupCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('setup');
        $this->setDescription('A setup wizard for your brand new wordress based project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	parent::execute($input, $output);
		$firstTimeSetup = true;
        // SETTING ROOT DIRECTORY PERMISSIONS
        $this->setDirectoryPermissions('project root', Config::projectRootPath());
		// CHECKING PREVIOUS SETUP
		if (Config::get()) {
			$firstTimeSetup = false;
		} else {
			$this->buildConfigTemplate();
		}
		// SETTING THEMES DIRECTORY PERMISSIONS
		$this->setDirectoryPermissions('themes', Config::projectRootPath() . Config::get('themes_path'));
		// CREATING THEME DIRECTORY
		$this->createThemeDirectory();
		// SETTING THEME DIRECTORY PERMISSIONS
		$this->setDirectoryPermissions(Config::get('theme_name') . ' theme', Config::projectRootPath() . Config::get('themes_path') . Config::get('theme_name'), true);
		// ADDING THEME FILES
        if ($firstTimeSetup) {
			// COPYING THEME BOILERPLATE FILES
			$this->copyThemeBoilerplate();
			// CREATING style.css
            $this->buildStyleTemplate();
        }
    }

	/**
	 * Directory permission modifier
	 *
	 * @param $name
	 * @param $path
	 * @param bool $recursive
	 */
    private function setDirectoryPermissions($name, $path, $recursive = false)
	{
		self::$dialog->write('Setting ' . $name . ' directory permissions... ', 'yellow', false);
		$baseCommand = $recursive ? 'sudo chmod -R 777 ' : 'sudo chmod 777 ';
		exec($baseCommand . $path, $out, $error);
		if (!$error) {
			self::$dialog->write('Done', 'green');
		} else {
			self::$dialog->write('Fail', 'red');
		}
	}

	/**
	 * Handles config template building
	 */
	private function buildConfigTemplate()
	{
		$configTemplate = new ConfigTemplateBuilder(self::$dialog);
		$configTemplate->setFilename('wordress-config.json');
		$configTemplate->create();
		Config::read();
	}

	/**
	 * Handles theme directory creation
	 */
	private function createThemeDirectory()
	{
		self::$dialog->write('Creating theme directory... ', 'yellow', false);
		$command = 'mkdir ' . Config::projectRootPath() . Config::get('themes_path') . Config::get('theme_name');
		exec($command, $out, $error);
		if (!$error) {
			self::$dialog->write('Done', 'green');
		} else {
			self::$dialog->write('Fail', 'red');
			exit();
		}
	}

	/**
	 * Handles theme boilerplate files copy
	 */
	private function copyThemeBoilerplate()
	{
		$boilerplate = Boilerplate::copy();
		self::$dialog->write('Copying theme boilerplate files... ', 'yellow', false);
		if ($boilerplate) {
			self::$dialog->write('Done', 'green');
		} else {
			self::$dialog->write('Fail', 'red');
		}
	}

	/**
	 * Handles style template building
	 */
	private function buildStyleTemplate()
	{
		$styleTemplate = new StyleTemplateBuilder(self::$dialog);
		$styleTemplate->setFilename('style.css');
		$styleTemplate->create();
	}
}
