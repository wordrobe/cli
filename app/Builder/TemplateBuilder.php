<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\TemplateManager;
use Wordrobe\Helper\Config;

/**
 * Class TemplateBuilder
 * @package Wordrobe\Builder
 */
abstract class TemplateBuilder extends Builder
{
	private $template;
	protected $dirname;
	protected $filename;

	/**
	 * TemplateCreator constructor
	 */
	function __construct()
	{
		$this->configure();
		$this->wizard();
		$this->fill();
		$this->save();
	}

	/**
	 * Handles template configuration
	 */
	protected function configure()
	{
		throw new Exception('You must override the configure() method in the concrete builder class.');
	}

	/**
	 * Handles template filling
	 */
	protected function fill()
	{
		throw new Exception('You must override the fill() method in the concrete builder class.');
	}

	/**
	 * Dirname setter
	 *
	 * @param $path
	 * @param bool $theme
	 */
	protected function setDirname($path, $theme = null)
	{
		$basepath = $theme ? Config::get('themes')[$theme]['path'] : Config::get('project_root');
		$this->dirname = $basepath . $path;
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
	 * Replaces template placeholder
	 *
	 * @param $placeholder
	 * @param $value
	 */
	protected function replace($placeholder, $value)
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
