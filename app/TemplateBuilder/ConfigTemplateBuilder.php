<?php

namespace Wordress\TemplateBuilder;

/**
 * Class ConfigTemplateBuilder
 * @package Wordress\TemplateBuilder
 */
class ConfigTemplateBuilder extends TemplateBuilder
{
	protected function configure()
	{
		$this->setTemplate('config');
		$this->setPath('/', true);
		$this->setExtension('json');
	}

	protected function build()
	{
		$this->setThemesPath();
		$this->setThemeName();
		$this->setTemplateEngine();
	}

	/**
	 * Themes path setter
	 */
	private function setThemesPath()
	{
		$path = $this->dialog->getAnswer('Please enter themes path (e.g. wp-content/themes):');
		if (empty($path)) {
			$this->dialog->write('Error: themes path is required! Unable to continue.', 'red');
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
	private function setThemeName()
	{
		$name = $this->dialog->getAnswer('Please enter theme name (e.g. my-theme):');
		if (empty($name)) {
			$this->dialog->write('Error: theme name is required! Unable to continue.', 'red');
			exit();
		}
		$name = self::normalizeString($name);
		$name = str_replace(' ', '-', $name);
		$this->fill('{THEME_NAME}', $name);
	}

	/**
	 * Template engine setter
	 */
	private function setTemplateEngine()
	{
		$engine = $this->dialog->getChoice('Please choose template engine:', array('Twig (Timber)', 'PHP (Standard Wordpress)'));
		if ($engine === 'Timber/Twig') {
			$this->fill('{TEMPLATE_ENGINE}', 'twig');
		} else {
			$this->fill('{TEMPLATE_ENGINE}', 'standard');
		}
	}
}
