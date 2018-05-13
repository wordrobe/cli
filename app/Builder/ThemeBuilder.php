<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Builder\StyleBuilder;

/**
 * Class themeBuilder
 * @package Wordrobe\Builder
 */
class ThemeBuilder extends Builder
{
	/**
	 * Handles theme creation
	 */
	protected function wizard()
	{
		$this->askForThemeName();
		$this->askForTemplateEngine();
	}

	/**
	 * Theme name setter
	 */
	private function askForThemeName()
	{
		$name = Dialog::getAnswer('Please enter theme name (e.g. my-theme):');
		if (empty($name)) {
			Dialog::write('Theme name is required!', 'red');
			return $this->askForThemeName();
		}
		$this->fill('{THEME_NAME}', StringsManager::joinWordsBy($name, '-'));
	}

	/**
	 * Template engine setter
	 */
	private function askForTemplateEngine()
	{
		$engine = Dialog::getChoice('Please choose template engine:', ['Twig (Timber)', 'PHP (Standard Wordpress)']);
		if ($engine === 'Twig (Timber)') {
			$this->fill('{TEMPLATE_ENGINE}', 'timber');
		} else {
			$this->fill('{TEMPLATE_ENGINE}', 'standard');
		}
	}

	// COPYING THEME BOILERPLATE FILES
BoilerplateManager::copyFiles();

	// CREATING style.css
$this->buildStyleTemplate();
}
