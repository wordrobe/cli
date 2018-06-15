<?php

class Theme {
  /**
   * Theme support setter
   */
  public static function setSupport()
  {
    add_theme_support('html5');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('post-formats', ['gallery', 'link', 'image', 'quote', 'video', 'audio']);
  }

  /**
   * Handles scripts and styles enqueueing
   */
  public static function enqueueAssets()
  {
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/main.css');
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/main.js', [], '1.0.0', true);
  }

  /**
   * Hides pages from admin menu
   */
  public static function hideMenuPages()
  {
    remove_menu_page('edit-comments.php'); // just an example
  }

  /**
   * Overrides ACF exports directory
   * @param $path
   * @return string
   */
  public static function enableCustomFieldsAutosave()
  {
    return get_stylesheet_directory() . '/includes/custom-fields';
  }

  /**
   * Overrides ACF exports directory
   * @param $paths
   * @return array
   */
  public static function enableCustomFieldsAutoload($paths)
  {
    $paths[0] = get_stylesheet_directory() . '/includes/custom-fields';
    return $paths;
  }

  /**
   * Sets theme configurations
   */
  public static function init()
  {
    add_action('init', 'Theme::setSupport');
    add_action('wp_enqueue_scripts', 'Theme::enqueueAssets');
    add_action('admin_menu', 'Theme::hideMenuPages');

    if (class_exists('acf')) {
      add_filter('acf/settings/save_json', 'Theme::enableCustomFieldsAutosave');
      add_filter('acf/settings/load_json', 'Theme::enableCustomFieldsAutoload');
    }
  }
}

add_action('after_setup_theme', 'Theme::init');
