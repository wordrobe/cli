<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\StringsManager;

/**
 * Class Plugin
 * @package Wordrobe\Entity
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
   */
  function __construct($plugin_name, $plugin_uri, $description, $version, $author, $author_uri, $license, $license_uri, $text_domain)
  {
    $this->plugin_name = $plugin_name;
    $this->plugin_uri = $plugin_uri;
    $this->description = $description;
    $this->version = $version;
    $this->author = $author;
    $this->author_uri = $author_uri;
    $this->license = $license;
    $this->license_uri = $license_uri;
    $this->text_domain = $text_domain;

    spl_autoload_register([$this, 'autoload']);
  }

  /**
   * Handles plugin src autoload
   * @param $classname
   */
  public function autoload($classname)
  {
    $file = str_replace('\\', '/', sprintf('%s/src/%s.php', plugins_url() . '/' . StringsManager::toKebabCase($this->plugin_name), $classname));
    if (file_exists($file)) {
      include_once $file;
    }
  }

  /**
   * Add a plugin's menu page
   * @param array $args
   * @param null|callable $render
   */
  public function addMenuPage($args, $render = null)
  {
    $page_title = $args['page_title'] ?: $this->plugin_name;
    $menu_title = $args['menu_title'] ?: $this->plugin_name;
    $menu_slug = $args['menu_slug'] ?: StringsManager::toKebabCase($menu_title);
    $capability = $args['capability'];
    $icon = $args[''] ?: 'none';
    $position = $args[''] ?: null;
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $render, $icon, $position);
  }

  /**
   * Registers and enqueues plugin's assets
   * @param $assets
   */
  public function addAssets($assets)
  {
    foreach ($assets as $asset) {
      $folder_name = StringsManager::toKebabCase($this->plugin_name);
      $filepath = plugins_url() . '/' . $folder_name . '/' . $asset['path'] . '/' . $asset['filename'];
      $asset_id = $folder_name . '.' . $asset['filename'];

      if ($asset['type'] === 'style') {
        wp_register_style($asset_id, $filepath);
        wp_enqueue_style($asset_id);
      } else if ($asset['type'] === 'script') {
        wp_register_script($asset_id, $filepath, $asset['dependencies'], false, true);
        wp_enqueue_script($asset_id);
      }
    }
  }
}
