<?php

namespace Wordress\TemplateBuilder;

use Wordress\Helper\Config;

/**
 * Class StyleTemplateBuilder
 * @package Wordress\TemplateBuilder
 */
class StyleTemplateBuilder extends TemplateBuilder
{

	protected function configure()
	{
		$this->setTemplate('style');
		$this->setPath('/');
		$this->setExtension('css');
	}

	protected function build()
	{
		$this->setThemeName();
		$this->setThemeDescription();
		$this->setTextDomain();
	}

	private function setThemeName()
	{
		$name = self::normalizeString(Config::get('theme_name'));
		$name = ucwords($name);
		$this->fill('{THEME_NAME}', $name);
	}

	private function setThemeDescription()
	{
		$name = self::normalizeString(Config::get('theme_name'));
		$name = ucwords($name);
		$description = 'The WP Theme for ' . $name . ' project';
		$description = $this->dialog->getAnswer('Please enter theme description [' . $description . ']:', $description);
		$this->fill('{THEME_DESCRIPTION}', $description);
	}

	private function setTextDomain()
	{
		$this->fill('{THEME_TEXTDOMAIN}', Config::get('theme_name'));
	}
}
