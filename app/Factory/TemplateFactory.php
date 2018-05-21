<?php

namespace Wordrobe\Factory;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;

abstract class TemplateFactory
{
	/**
	 * Asks for target theme
	 * @return mixed
	 */
	protected static function askForTheme()
	{
		$themes = Config::get('themes');

		switch (count($themes)) {
			case 0:
				Dialog::write("Your project doesn't have any themes yet. Please run 'vendor/bin/wordrobe add theme' first.", 'red');
				exit();
			case 1:
				return array_keys($themes)[0];
			default:
				return Dialog::getChoice('Please choose the theme you want to add the content to:', array_keys($themes), null, 'yellow');
		}
	}
}