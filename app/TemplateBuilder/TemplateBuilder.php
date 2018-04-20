<?php

namespace Wordrobe\TemplateBuilder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\TemplateManager;

/**
 * Class TemplateBuilder
 * @package Wordrobe\TemplateBuilder
 */
abstract class TemplateBuilder
{
	private $template;
	private $filename;
	private $dirname;

	/**
	 * TemplateCreator constructor
	 */
	function __construct()
	{
		$this->configure();
		$this->wizard();
		$this->save();
	}

	/**
	 * Template setter
	 *
	 * @param $template
	 */
	protected function setTemplate($template)
	{
		$this->template = TemplateManager::getTemplateContent($template);
	}

	/**
	 * Filename setter
	 *
	 * @param $filename
	 */
	protected function setFilename($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * Dirname setter
	 *
	 * @param $path
	 * @param bool $atRoot
	 */
	protected function setDirname($path, $atRoot = false)
	{
		$basepath = $atRoot ? PROJECT_ROOT : Config::get('theme_root');
		$this->dirname = $basepath . $path;
	}

	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		throw new Exception('You must override the configure() method in the concrete builder class.');
	}

	/**
	 * Provides a template build wizard
	 */
	protected function wizard()
	{
		throw new Exception('You must override the wizard() method in the concrete builder class.');
	}

	/**
	 * Replaces template placeholder
	 *
	 * @param $placeholder
	 * @param $value
	 */
	protected function fill($placeholder, $value)
	{
		$this->template = str_replace($placeholder, $value, $this->template);
	}

	/**
	 * Saves template in a file
	 */
	private function save()
	{
		$filepath = $this->dirname . $this->filename;
		FilesManager::writeFile($filepath, $this->template);
	}
}
