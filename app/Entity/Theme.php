<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;

/**
 * Class Theme
 * @package Wordrobe\Entity
 */
class Theme
{
  protected $theme_name;
  protected $theme_uri;
  protected $description;
  protected $tags;
  protected $version;
  protected $author;
  protected $author_uri;
  protected $license;
  protected $license_uri;
  protected $namespace;
  protected $text_domain;
  protected $folder_name;
  protected $path;

  /**
   * Theme constructor.
   * @param string $theme_name
   * @param string $theme_uri
   * @param string $description
   * @param string $tags
   * @param string $version
   * @param string $author
   * @param string $author_uri
   * @param string $license
   * @param string $license_uri
   * @param string $namespace
   * @param string $text_domain
   * @param string $folder_name
   * @throws \Exception
   */
  public function __construct($theme_name, $theme_uri, $description, $tags, $version, $author, $author_uri, $license, $license_uri, $namespace, $text_domain, $folder_name)
  {
    $themes_path = Config::get('themes-path', true);
    $this->theme_name = $theme_name;
    $this->theme_uri = $theme_uri;
    $this->description = $description;
    $this->tags = $tags;
    $this->version = $version;
    $this->author = $author;
    $this->author_uri = $author_uri;
    $this->license = $license;
    $this->license_uri = $license_uri;
    $this->namespace = $namespace;
    $this->text_domain = $text_domain;
    $this->folder_name = $folder_name;
    $this->path = Config::getRootPath() . "/$themes_path/$this->folder_name";
  }

  /**
   * Installs theme
   * @param bool $override
   * @throws \Exception
   */
  public function install($override = false)
  {
    $can_install = !FilesManager::directoryExists($this->path);

    if (!$can_install) {
      switch ($override) {
        case 'force':
          $can_install = true;
          break;
        case 'ask':
          $can_install = Dialog::getConfirmation('Attention: a theme already exists at ' . $this->path . '! Do you want to override it?', false, 'red');
          break;
        default:
          break;
      }
    }

    if ($can_install) {
      FilesManager::createDirectory($this->path);
      $this->copyBoilerplate();
      $this->addThemeManager();
      $this->addFunctions();
      $this->addStylesheet();
      $this->updateConfig();
    } else {

      if (is_null(Config::get("themes.$this->folder_name"))) {
        $this->updateConfig();
      }

      throw new \Exception('Theme installation aborted.');
    }
  }

  /**
   * Adds ThemeManager.php to theme
   * @throws \Exception
   */
  protected function addThemeManager()
  {
    $functions = new Template('theme-manager', [
      '{NAMESPACE}' => $this->namespace,
      '{TEXT_DOMAIN}' => $this->text_domain,
      '{ROOT_PATH}' => Config::getRelativeRootPath($this->path)
    ]);
    $functions->save("$this->path/core/ThemeManager.php", 'force');
  }

  /**
   * Adds functions.php to theme
   * @throws \Exception
   */
  protected function addFunctions()
  {
    $functions = new Template('theme-functions', [
      '{NAMESPACE}' => $this->namespace,
      '{ROOT_PATH}' => Config::getRelativeRootPath($this->path)
    ]);
    $functions->save("$this->path/functions.php", 'force');
  }

  /**
   * Adds style.css to theme
   * @throws \Exception
   */
  protected function addStylesheet()
  {
    $stylesheet = new Template('theme-stylesheet', [
      '{THEME_NAME}' => $this->theme_name,
      '{THEME_URI}' => $this->theme_uri,
      '{DESCRIPTION}' => $this->description,
      '{TAGS}' => $this->tags,
      '{VERSION}' => $this->version,
      '{AUTHOR}' => $this->author,
      '{AUTHOR_URI}' => $this->author_uri,
      '{LICENSE}' => $this->license,
      '{LICENSE_URI}' => $this->license_uri,
      '{TEXT_DOMAIN}' => $this->text_domain
    ]);
    $stylesheet->save("$this->path/style.css", 'force');
  }

  /**
   * Adds theme params to Config
   * @throws \Exception
   */
  protected function updateConfig()
  {
    $themeConfig = new Template('theme-config', [
      '{NAMESPACE}' => $this->namespace,
      '{TEXT_DOMAIN}' => $this->text_domain
    ]);
    $content = $themeConfig->getContent();
    Config::set("themes.$this->folder_name", json_decode($content));
  }

  /**
   * Copies theme boilerplate
   * @throws \Exception
   */
  private function copyBoilerplate()
  {
    $boilerplatePath = dirname(__DIR__) . '/boilerplate';
    FilesManager::copyFiles($boilerplatePath, $this->path);
  }
}
