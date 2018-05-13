<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class ConfigBuilder
 * @package Wordrobe\Builder
 */
class ConfigBuilder extends TemplateBuilder
{
	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		$this->setTemplate('project_settings');
		$this->setFilename(Config::FILENAME);
		$this->setDirname('/', true);
	}

	/**
	 * Provides a template build wizard
	 */
	protected function wizard()
	{
		$this->askForProjectRoot();
		$this->askForThemesPath();
		$this->askForNewTheme();
	}

	/**
	 * Project root setter
	 */
	private function askForProjectRoot()
	{
		$path = Dialog::getAnswer('Please enter project root path [' . getcwd() . ']:', getcwd());
		$this->fill('{PROJECT_ROOT}', rtrim($path, '/'));
		// SETTING PERMISSIONS
		FilesManager::setPermissions($path, 0755);
	}

	/**
	 * Themes path setter
	 */
	private function askForThemesPath()
	{
		$path = Dialog::getAnswer('Please enter themes path (e.g. wp-content/themes):');
		if (empty($path)) {
			Dialog::write('Error: themes path is required!', 'red');
			return $this->askForThemesPath();
		}
		$this->fill('{THEMES_PATH}', trim($path, '/'));
	}

	/**
	 * Handles new theme creation
	 * @return null|\Wordrobe\Builder\ThemeBuilder
	 */
	private function askForNewTheme()
	{
		$addNewTheme = Dialog::getConfirmation('Do you want to create a brand new theme right now?', true);
		return $addNewTheme ? new ThemeBuilder() : NULL;
	}
}
