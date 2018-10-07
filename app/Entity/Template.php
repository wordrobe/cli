<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\Dialog;

/**
 * Class Template
 * @package Wordrobe\Entity
 */
class Template
{
  protected $path;
  protected $content;
  
  /**
   * Template constructor.
   * @param null|string $path
   * @param string $model
   * @param null|array $replacements
   * @throws \Exception
   */
  public function __construct($path, $model, $replacements = null)
  {
    $this->path = $path;
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
   * @param string $content
   */
  public function setContent($content)
  {
    $this->content = $content;
  }
  
  /**
   * Replaces template placeholder
   * @param string $placeholder
   * @param string $value
   */
  public function fill($placeholder, $value)
  {
    $this->content = str_replace($placeholder, $value, $this->content);
  }
  
  /**
   * Saves template in a file
   * @param string $filename
   * @param mixed $override
   * @throws \Exception
   */
  public function save($filename, $override = false)
  {
    if ($this->path) {
      $filename = $this->path . '/' . $filename;
      $force_override = false;

      switch ($override) {
        case 'force':
          $force_override = true;
          break;
        case 'ask':
          if (FilesManager::fileExists($filename)) {
            $force_override = Dialog::getConfirmation('Attention: ' . $filename . ' already exists! Do you want to override it?', false, 'red');
          }
          break;
        default:
          break;
      }

      FilesManager::writeFile($filename, $this->content, $force_override);
      FilesManager::deleteFile($this->path . '/.gitkeep');
    }
  }
  
  /**
   * Model content getter
   * @param string $model
   * @return string
   * @throws \Exception
   */
  private static function getModelContent($model)
  {
    return FilesManager::readFile(dirname(__DIR__) . '/templates/' . $model);
  }
}
