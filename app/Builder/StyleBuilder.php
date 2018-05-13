<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class StyleBuilder
 * @package Wordrobe\Builder
 */
class StyleBuilder extends TemplateBuilder
{
	private $theme_name;
	private $theme_description;
	private $parent_theme;

	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		$this->setTemplate('style');
		$this->setFilename('style.css');
		$this->setDirname('/');
		$this->setThemeName();
	}

	/**
	 * Provides a wizard to retrieve all needed information to fill the template
	 */
	protected function wizard()
	{
		$this->theme_description = $this->askForThemeDescription();
		$this->parent_theme = $this->askForParentTheme();
	}

	/**
	 * Fills the template
	 */
	protected function fill() {
		$this->replace('{THEME_NAME}', $this->theme_name);
		$this->replace('{THEME_DESCRIPTION}', $this->theme_description);
		$this->replace('{THEME_TEXTDOMAIN}', Config::get('theme_name'));
	}

	/**
	 * Theme name setter
	 */
	private function setThemeName()
	{
		$name = StringsManager::dashesToSpace(Config::get('theme_name'));
		$this->theme_name = ucwords($name);
	}

	/**
	 * Theme description getter
	 */
	private function askForThemeDescription()
	{
		$description = 'The WP Theme for ' . $this->theme_name . ' project';
		return Dialog::getAnswer('Please enter theme description [' . $description . ']:', $description);
	}

	private function askForParentTheme()
	{

	}
}
