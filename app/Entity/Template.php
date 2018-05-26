<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;

/**
 * Class Template
 * @package Wordrobe\Model
 */
class Template
{
    protected $content;

    /**
     * Template constructor.
     * @param $model
     * @param $replacements
     */
    public function __construct($model, $replacements = null)
    {
        $this->content = self::getModelContent($model);
        // auto-fill
        if (is_array($replacements)) {
            foreach ($replacements as $placeholder => $replacement) {
                $this->fill($placeholder, $replacement);
            }
        }
    }

    /**
     * Content getter
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Content setter
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Replaces template placeholder
     * @param $placeholder
     * @param $value
     */
    public function fill($placeholder, $value)
    {
        $this->content = str_replace($placeholder, $value, $this->content);
    }

	/**
	 * Saves template in a file
	 * @param $filepath
	 * @return bool
	 */
    public function save($filepath)
    {
        try {
            $written = FilesManager::writeFile($filepath, $this->content);
        } catch (\Exception $e) {
            Dialog::write($e->getMessage(), 'red');
            exit();
        }

		if ($written) {
			Dialog::write("$filepath written!", 'cyan');
		}

		return $written;
    }

    /**
     * Model content getter
     * @param $model
     * @return string
     * @throws \Exception
     */
    private static function getModelContent($model)
    {
        $templateFile = TEMPLATES_MODELS_PATH . '/' . $model;
        try {
            $content = FilesManager::readFile($templateFile);
        } catch (\Exception $e) {
            Dialog::write($e->getMessage(), 'red');
            exit();
        }
        return $content;
    }
}
