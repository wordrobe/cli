<?php

namespace Wordress\TemplateBuilder;

use Wordress\Helper\Dialog;
use Wordress\Helper\Config;

/**
 * Class TemplateBuilder
 * @package Wordress\TemplateBuilder
 */
class TemplateBuilder
{
	protected $name;
	protected $extension;
	protected $type;
	protected $template;
	protected $path;
	protected $dialog;

	/**
	 * TemplateCreator constructor.
	 *
	 * @param Dialog $dialog
	 */
	function __construct(Dialog $dialog)
	{
		// DIALOG
		$this->dialog = $dialog;
		// CONFIGURE
		$this->configure();
	}

	/**
	 * Filename setter
	 *
	 * @param $filename
	 */
	public function setFilename($filename)
	{
		// CHECKING INITIAL DOT
		$hasInitialDot = false;
		if (preg_match('/^\..+/', $filename)) { // removing initial dot
			$hasInitialDot = true;
			$filename = substr($filename, 1);
		}
		// SPLITTING FILENAME
		$filenameParts = explode('.', $filename);
		// SETTING NAME
		$name = self::normalizeString($filenameParts[0]);
		$name = str_replace(' ', '-', $name);
		if ($hasInitialDot) {
			$this->name = '.' . $name;
		} else {
			$this->name = $name;
		}
		// SETTING EXTENSION IF IT'S NOT SET YET
		if ($this->extension === NULL) {
			// REMOVING BASENAME FROM FILENAME PARTS
			array_shift($filenameParts);
			// SETTING FILE EXTENSION
			$extension = implode(' ', $filenameParts);
			$extension = self::normalizeString($extension);
			$extension = str_replace(' ', '.', $extension);
			if (!empty($extension)) {
				$this->extension = $extension;
			} else {
				// ASKING FOR FILE EXTENSION
				$extension = $this->dialog->getAnswer('Please provide file extension [php]:', 'php');
				if (preg_match('/^\..+/', $extension)) { // removing initial dot
					$extension = substr($extension, 1);
				}
				$this->extension = $extension;
			}
		}
	}

	/**
	 * Creates template file
	 *
	 * @param bool $forceOverride
	 */
	public function create($forceOverride = false)
	{
		// CHECKING NAME
		if ($this->name === NULL) {
			$name = $this->dialog->getAnswer('Please provide file basename (e.g. wp-skeleton):');
			if (empty($name)) {
				$this->dialog->write('Error: file basename is required! Unable to continue.', 'red');
				exit();
			}
			$this->setFilename($name);
		}
		// CHECKING EXISTENCE
		if (!$forceOverride) {
			if (!$this->isWritable()) {
				exit();
			}
		}
		// STARTING TEMPLATE BUILDING WIZARD
		$this->build();
		// WRITING TEMPLATE FILE
		$this->write();
	}

	/**
	 * Provides a string normalization
	 *
	 * @param $string
	 * @return string
	 */
	public static function normalizeString($string)
	{
		$string = preg_replace('/(?<!^)\.[a-z0-9\.]+$/', '', $string); // file extension
		$string = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $string); // symbols
		$string = preg_replace('/\s+/', ' ', $string); // multiple white spaces
		$string = trim($string);
		return strtolower($string);
	}

	/**
	 * Template setter
	 *
	 * @param $filename
	 */
	protected function setTemplate($filename)
	{
		$filepath = realpath(__DIR__ . '/../Templates/' . $filename);
		if (!file_exists($filepath)) {
			$this->dialog->write('Error: "' . $filename . '" template not found! Unable to continue.', 'red');
			exit();
		}
		$this->type = $filename;
		$this->template = file_get_contents($filepath);
	}

	/**
	 * Path setter
	 *
	 * @param $path
	 * @param bool $fromRoot
	 */
	protected function setPath($path, $fromRoot = false)
	{
		if ($fromRoot) {
			$this->path = Config::projectRootPath() . $path;
		} else {
			$this->path = Config::projectRootPath() . Config::get('themes_path') . Config::get('theme_name') . $path;
		}
	}

	/**
	 * Extension setter
	 *
	 * @param $extension
	 */
	protected function setExtension($extension)
	{
		$this->extension = $extension;
	}

	/**
	 * Provides a configuration for the builder
	 */
	protected function configure()
	{

	}

	/**
	 * Provides a template build wizard
	 */
	protected function build()
	{

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
	 * Writes template file
	 */
	protected function write()
	{
		// CHECKING DIRECTORY EXISTENCE
		if (!is_dir($this->path)) {
			exec('mkdir ' . $this->path);
		}
		// WRITING FILE
		$filename = $this->name;
		if (!empty($this->extension)) {
			$filename .= '.' . $this->extension;
		}
		$filepath = $this->path . $filename;
		$this->dialog->write('Writing ' . $filepath . '...', 'yellow', false);
		$file = fopen($filepath, 'w');
		if (fwrite($file, $this->template)) {
			fclose($file);
			$this->dialog->write('Done', 'green');
		} else {
			$this->dialog->write('Fail', 'red');
		}
	}

	/**
	 * Checks file existence and provides an override confirmation question
	 *
	 * @return bool
	 */
	private function isWritable()
	{
		$filename = $this->name . '.' . $this->extension;
		$filepath = $this->path . $filename;
		if (file_exists($filepath)) {
			return $this->dialog->getConfirmation('Attention: the ' . $this->type . ' ' . $filename . ' already exists! Do you want to override it?', false, 'red');
		}
		return true;
	}
}
