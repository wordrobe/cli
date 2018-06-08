<?php

namespace Wordrobe\Wrapper;

use Wordrobe\Config;
use Wordrobe\Helper\FilesManager;

/**
 * Class Theme
 * @package Wordrobe\Wrapper
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
  protected $text_domain;
  protected $folder_name;
  protected $template_engine;
  protected $path;
  
  /**
   * Theme constructor.
   * @param $theme_name
   * @param $theme_uri
   * @param $description
   * @param $tags
   * @param $version
   * @param $author
   * @param $author_uri
   * @param $license
   * @param $license_uri
   * @param $text_domain
   * @param $folder_name
   * @param $template_engine
   * @throws \Exception
   */
  public function __construct($theme_name, $theme_uri, $description, $tags, $version, $author, $author_uri, $license, $license_uri, $text_domain, $folder_name, $template_engine)
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
    $this->text_domain = $text_domain;
    $this->folder_name = $folder_name;
    $this->template_engine = $template_engine;
    $this->path = PROJECT_ROOT . "/$themes_path/$this->folder_name";
  }
  
  /**
   * Installs theme
   * @throws \Exception
   */
  public function install()
  {
    FilesManager::createDirectory($this->path);
    $this->copyBoilerplate();
    $this->addFunctions();
    $this->addStylesheet();
    $this->updateConfig();
  }
  
  /**
   * Adds functions.php to theme
   * @throws \Exception
   */
  protected function addFunctions()
  {
    $functions = new Template('theme-functions', ['{PROJECT_ROOT}' => Config::getRelativeRootPath($this->path)]);
    $functions->save("$this->path/functions.php");
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
    $stylesheet->save("$this->path/style.css");
  }
  
  /**
   * Adds theme params to Config
   * @return array
   * @throws \Exception
   */
  protected function updateConfig()
  {
    $themeConfig = new Template('theme-config', ['{TEMPLATE_ENGINE}' => $this->template_engine]);
    $content = $themeConfig->getContent();
    return Config::set("themes.$this->folder_name", json_decode($content));
  }
  
  /**
   * Copies theme boilerplate
   * @throws \Exception
   */
  private function copyBoilerplate()
  {
    $themeBoilerplatesPath = dirname(__DIR__) . '/ThemeBoilerplates';
    $commonBoilerplateFiles = $themeBoilerplatesPath . '/commons';
    $specificBoilerplateFiles = $themeBoilerplatesPath . '/' . $this->template_engine;
    FilesManager::copyFiles($commonBoilerplateFiles, $this->path);
    FilesManager::copyFiles($specificBoilerplateFiles, $this->path);
  }
}
