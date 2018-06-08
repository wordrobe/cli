<?php

namespace Wordrobe\Wrapper;

use Wordrobe\Config;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\StringsManager;

/**
 * Class Plugin
 * @package Wordrobe\Wrapper
 */
class Plugin
{
  private $plugin_name;
  private $plugin_uri;
  private $description;
  private $version;
  private $author;
  private $author_uri;
  private $license;
  private $license_uri;
  private $text_domain;
  private $folder_name;
  private $path;

  /**
   * Plugin constructor.
   * @param $plugin_name
   * @param $plugin_uri
   * @param $description
   * @param $version
   * @param $author
   * @param $author_uri
   * @param $license
   * @param $license_uri
   * @param $text_domain
   * @param $folder_name
   * @throws \Exception
   */
  function __construct($plugin_name, $plugin_uri, $description, $version, $author, $author_uri, $license, $license_uri, $text_domain, $folder_name)
  {
    $plugins_path = Config::get('plugins-path', true);
    $this->plugin_name = $plugin_name;
    $this->plugin_uri = $plugin_uri;
    $this->description = $description;
    $this->version = $version;
    $this->author = $author;
    $this->author_uri = $author_uri;
    $this->license = $license;
    $this->license_uri = $license_uri;
    $this->text_domain = $text_domain;
    $this->folder_name = $folder_name;
    $this->path = PROJECT_ROOT . "/$plugins_path/$this->folder_name";
  }

  /**
   * Installs plugin
   * @throws \Exception
   */
  public function install()
  {
    $this->copyBoilerplate();
    $this->addIndex();
  }

  /**
   * @throws \Exception
   */
  private function addIndex()
  {
    $index = new Template('plugin-index', [
      '{PROJECT_ROOT}' => Config::getRelativeRootPath($this->path),
      '{PLUGIN_VAR}' => StringsManager::toSnakeCase($this->plugin_name),
      '{PLUGIN_NAME}' => $this->plugin_name,
      '{PLUGIN_URI}' => $this->plugin_uri,
      '{DESCRIPTION}' => $this->description,
      '{VERSION}' => $this->version,
      '{AUTHOR}' => $this->author,
      '{AUTHOR_URI}' => $this->author_uri,
      '{LICENSE}' => $this->license,
      '{LICENSE_URI}' => $this->license_uri,
      '{TEXT_DOMAIN}' => $this->text_domain
    ]);
    $index->save("$this->path/index.php");
  }

  /**
   * Copies plugin boilerplate
   * @throws \Exception
   */
  private function copyBoilerplate()
  {
    $boilerplateFiles = dirname(__DIR__) . '/PluginBoilerplate';
    FilesManager::copyFiles($boilerplateFiles, $this->path);
  }
}
