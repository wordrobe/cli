<?php

namespace Wordrobe\Helper;

/**
 * Class TemplateLocator
 * @package Wordrobe\Helper
 */
final class TemplateLocator
{
  /**
   * @var array TEMPLATES
   */
  private const TEMPLATES = [
    'single',
    'page',
    'category',
    'tag',
    'taxonomy',
    'author',
    'search',
    'archive',
    '404',
    'index'
  ];

  /**
   * Retrieves a template
   * @param string $template_name
   * @param string $theme
   * @param mixed $aux
   * @param string $fallback
   * @return string
   * @throws \Exception
   */
  public static function get($template_name, $theme, $aux, $fallback)
  {
    $controllers_dir = Config::getRootPath() . '/' . Config::get('themes-path', true) . "/$theme/controllers";

    if (!FilesManager::directoryExists($controllers_dir)) {
      throw new \Exception("Error: the directory $controllers_dir doesn't exist. Unable to locate template.");
    }

    $template_name = strtolower($template_name);

    if (!in_array($template_name, self::TEMPLATES)) {
      throw new \Exception("Error: unknown template $template_name. Unable to locate it.");
    }

    switch ($template_name) {
      case 'single':
        $template_path = self::getSingleTemplate($controllers_dir, $aux);
        break;
      case 'page':
        $template_path = self::getPageTemplate($controllers_dir, $aux);
        break;
      case 'category':
        $template_path = self::getCategoryTemplate($controllers_dir, $aux);
        break;
      case 'tag':
        $template_path = self::getTagTemplate($controllers_dir, $aux);
        break;
      case 'taxonomy':
        $template_path = self::getTaxonomyTemplate($controllers_dir, $aux);
        break;
      case 'author':
        $template_path = self::getAuthorTemplate($controllers_dir);
        break;
      case 'search':
        $template_path = self::getSearchTemplate($controllers_dir);
        break;
      case 'archive':
        $template_path = self::getArchiveTemplate($controllers_dir, $aux);
        break;
      case '404':
        $template_path = self::get404Template($controllers_dir);
        break;
      case 'index':
      default:
        $template_path = $controllers_dir . '/index.php';
    }

    return FilesManager::fileExists($template_path) ? $template_path : $fallback;
  }

  /**
   * Retrieves single template
   * @param string $dirname
   * @param object $post
   * @return string
   */
  private static function getSingleTemplate($dirname, $post)
  {
    if (property_exists($post, 'post_type')) {
      $template = $dirname . "/$post->post_type.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . '/single.php';
    } else {
      $template = $dirname . '/single.php';
    }

    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves page template
   * @param string $dirname
   * @param string $template
   * @return string
   */
  private static function getPageTemplate($dirname, $template)
  {
    $template = $template ?: $dirname . '/page.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves category template
   * @param string $dirname
   * @param object $term
   * @return string
   */
  private static function getCategoryTemplate($dirname, $term)
  {
    if (property_exists($term, 'slug')) {
      $template = $dirname . "/category-$term->slug.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . '/category.php';
    } else {
      $template = $dirname . '/category.php';
    }

    $template = FilesManager::fileExists($template) ? $template : $dirname . '/archive.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves tag template
   * @param string $dirname
   * @param object $term
   * @return string
   */
  private static function getTagTemplate($dirname, $term)
  {
    if (property_exists($term, 'slug')) {
      $template = $dirname . "/tag-$term->slug.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . '/tag.php';
    } else {
      $template = $dirname . '/tag.php';
    }

    $template = FilesManager::fileExists($template) ? $template : $dirname . '/archive.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves taxonomy template
   * @param string $dirname
   * @param object $term
   * @return string
   */
  private static function getTaxonomyTemplate($dirname, $term)
  {
    if (property_exists($term, 'taxonomy') && property_exists($term, 'slug')) {
      $template = $dirname . "/taxonomy-$term->taxonomy-$term->slug.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . "/taxonomy-$term->taxonomy.php";
    } else if (property_exists($term, 'taxonomy')) {
      $template = $dirname . "/taxonomy-$term->taxonomy.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . '/taxonomy.php';
    } else {
      $template = $dirname . '/taxonomy.php';
    }

    if (property_exists($term, 'object_type')) {
      $template = FilesManager::fileExists($template) ? $template : $dirname . "/archive-$term->object_type.php";
    }

    $template = FilesManager::fileExists($template) ? $template : $dirname . '/archive.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves author template
   * @param string $dirname
   * @return string
   */
  private static function getAuthorTemplate($dirname)
  {
    $template = $dirname . '/author.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves search template
   * @param string $dirname
   * @return string
   */
  private static function getSearchTemplate($dirname)
  {
    $template = $dirname . '/search.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves archive template
   * @param string $dirname
   * @param object $post
   * @return string
   */
  private static function getArchiveTemplate($dirname, $post)
  {
    if (property_exists($post, 'name')) {
      $template = $dirname . "/archive-$post->name.php";
      $template = FilesManager::fileExists($template) ? $template : $dirname . '/archive.php';
    } else {
      $template = $dirname . '/archive.php';
    }

    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }

  /**
   * Retrieves 404 template
   * @param string $dirname
   * @return string
   */
  private static function get404Template($dirname)
  {
    $template = $dirname . '/404.php';
    return FilesManager::fileExists($template) ? $template : $dirname . '/index.php';
  }
}