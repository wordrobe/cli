<?php

namespace Wordrobe\TemplateBuilder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class StyleTemplateBuilder
 * @package Wordrobe\TemplateBuilder
 */
class StyleTemplateBuilder extends TemplateBuilder 
{
	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		$this->setTemplate('style');
		$this->setFilename('style.css');
		$this->setDirname('/');
	}

	/**
	 * Provides a template build wizard
	 */
	protected function wizard()
	{
		$this->askForThemeName();
		$this->askForThemeDescription();
		$this->askForTextDomain();
	}

	/**
	 * Theme name setter
	 */
	private function askForThemeName()
	{
		$name = StringsManager::normalize(Config::get('theme_name'));
		$this->fill('{THEME_NAME}', ucwords($name));
	}

	/**
	 * Theme description setter
	 */
	private function askForThemeDescription()
	{
		$name = StringsManager::normalize(Config::get('theme_name'));
		$description = 'The WP Theme for ' . ucwords($name) . ' project';
		$description = Dialog::getAnswer('Please enter theme description [' . $description . ']:', $description);
		$this->fill('{THEME_DESCRIPTION}', $description);
	}

	/**
	 * Theme text domain setter
	 */
	private function askForTextDomain()
	{
		$this->fill('{THEME_TEXTDOMAIN}', Config::get('theme_name'));
	}
}
