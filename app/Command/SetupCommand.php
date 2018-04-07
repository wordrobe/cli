<?php

namespace Wordress\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordress\Helper\Config;
use Wordress\Helper\Dialog;
use Wordress\Helper\Boilerplate;
use Wordress\TemplateBuilder\ConfigTemplateBuilder;
use Wordress\TemplateBuilder\StyleTemplateBuilder;

/**
 * Class SetupCommand
 * @package Wordress\Command
 */
class SetupCommand extends Command
{

	private static $dialog;

    protected function configure()
    {
        $this->setName('wordress:setup');
        $this->setDescription('A setup wizard for your brand new wordress based project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$firstTimeSetup = true;
		self::$dialog = new Dialog($this->getHelper('question'), $input, $output);

        // SETTING ROOT DIRECTORY PERMISSIONS
        $this->setDirectoryPermissions('project root', Config::projectRootPath());

        // GETTING CONFIG
        $config = Config::get();
		// CHECKING PREVIOUS SETUP
		if ($config) {
			$firstTimeSetup = false;
		} else {
			// CREATING wordress-config.json
			$this->buildConfigTemplate();
			$config = Config::get();
			if (!$config) {
				self::$dialog->write('Error: wordress-config.json not found. Unable to continue.', 'red');
				exit();
			}
		}

		// SETTING THEMES DIRECTORY PERMISSIONS
		$this->setDirectoryPermissions('themes', Config::projectRootPath() . Config::get('themes_path'));

		// CREATING THEME DIRECTORY
		$this->createThemeDirectory();

		// SETTING THEME DIRECTORY PERMISSIONS
		$this->setDirectoryPermissions(Config::get('theme_name') . ' theme', Config::projectRootPath() . Config::get('themes_path') . Config::get('theme_name'), true);

		// CREATING THEME FOLDER
        if ($firstTimeSetup) {
			// COPYING THEME BOILERPLATE FILES
			$this->copyThemeBoilerplate();
			// CREATING style.css
            $this->buildStyleTemplate();
        }
    }

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

	private function buildConfigTemplate()
	{
		$configTemplate = new ConfigTemplateBuilder(self::$dialog);
		$configTemplate->setFilename('wordress-config.json');
		$configTemplate->create();
	}

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

	private function buildStyleTemplate()
	{
		$styleTemplate = new StyleTemplateBuilder(self::$dialog);
		$styleTemplate->setFilename('style.css');
		$styleTemplate->create();
	}
}
