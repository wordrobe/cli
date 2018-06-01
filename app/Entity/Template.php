<?php

namespace Wordrobe\Entity;

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
   * @param bool $force_override
   * @return bool
   */
  public function save($filepath, $force_override = false)
  {
    return FilesManager::writeFile($filepath, $this->content, $force_override);
  }
  
  /**
   * Model content getter
   * @param $model
   * @return string
   * @throws \Exception
   */
  private static function getModelContent($model)
  {
    return FilesManager::readFile(TEMPLATES_MODELS_PATH . '/' . $model);
  }
}
