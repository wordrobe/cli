<?php

namespace Wordrobe\TemplateBuilder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class ConfigTemplateBuilder
 * @package Wordrobe\TemplateBuilder
 */
class ConfigTemplateBuilder extends TemplateBuilder
{
	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		$this->setTemplate('config');
		$this->setFilename(Config::FILENAME);
		$this->setDirname('/', true);
	}

	/**
	 * Provides a template build wizard
	 */
	protected function wizard()
	{
		$this->askForThemesPath();
		$this->askForThemeName();
		$this->askForTemplateEngine();
	}

	/**
	 * Themes path setter
	 */
	private function askForThemesPath()
	{
		$path = Dialog::getAnswer('Please enter themes path (e.g. wp-content/themes):');
		if (empty($path)) {
			Dialog::write('Error: themes path is required! Unable to continue.', 'red');
			exit();
		}
		if (substr($path, -1) !== '/') {
			$path .= '/'; // adding trailing slash if missing
		}
		$this->fill('{THEMES_PATH}', $path);
	}

	/**
	 * Theme name setter
	 */
	private function askForThemeName()
	{
		$name = Dialog::getAnswer('Please enter theme name (e.g. my-theme):');
		if (empty($name)) {
			Dialog::write('Error: theme name is required! Unable to continue.', 'red');
			exit();
		}
		$this->fill('{THEME_NAME}', StringsManager::toWordsJoinedBy($name, '-'));
	}

	/**
	 * Template engine setter
	 */
	private function askForTemplateEngine()
	{
		$engine = Dialog::getChoice('Please choose template engine:', ['Twig (Timber)', 'PHP (Standard Wordpress)']);
		if ($engine === 'Timber/Twig') {
			$this->fill('{TEMPLATE_ENGINE}', 'twig');
		} else {
			$this->fill('{TEMPLATE_ENGINE}', 'standard');
		}
	}
}
