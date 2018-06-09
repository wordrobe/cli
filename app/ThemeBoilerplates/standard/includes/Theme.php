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
}

add_action('init', 'Theme::setSupport');
add_action('wp_enqueue_scripts', 'Theme::enqueueAssets');
add_action('admin_menu', 'Theme::hideMenuPages');