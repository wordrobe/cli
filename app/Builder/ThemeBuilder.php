<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Dialog;
use Wordrobe\Wrapper\Theme;
use Wordrobe\Helper\StringsManager;

/**
 * Class ThemeBuilder
 * @package Wordrobe\Builder
 */
class ThemeBuilder implements Builder
{
  const TEMPLATE_ENGINES = [
    'timber',
    'standard'
  ];
  
  /**
   * Handles theme creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme_name = self::askForThemeName();
      $theme_uri = self::askForThemeURI();
      $description = self::askForDescription();
      $tags = self::askForTags();
      $version = self::askForVersion();
      $author = self::askForAuthor();
      $author_uri = self::askForAuthorURI();
      $license = self::askForLicense();
      $license_uri = self::askForLicenseURI();
      $text_domain = self::askForTextDomain($theme_name);
      $folder_name = self::askForFolderName($theme_name);
      $template_engine = self::askForTemplateEngine();
      self::build([
        'theme-name' => $theme_name,
        'theme-uri' => $theme_uri,
        'description' => $description,
        'tags' => $tags,
        'version' => $version,
        'author' => $author,
        'author-uri' => $author_uri,
        'license' => $license,
        'license-uri' => $license_uri,
        'text-domain' => $text_domain,
        'folder-name' => $folder_name,
        'template-engine' => $template_engine
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  
    Dialog::write('Theme installed!', 'green');
  }
  
  /**
   * Builds theme
   * @param array $params
   * @example ThemeBuilder::create([
   *  'theme-name' => $theme_name,
   *  'theme-uri' => $theme_uri,
   *  'description' => $description,
   *  'tags' => $tags,
   *  'version' => $version,
   *  'author' => $author,
   *  'author-uri' => $author_uri,
   *  'license' => $license,
   *  'license-uri' => $license_uri,
   *  'text-domain' => $text_domain,
   *  'folder-name' => $folder_name,
   *  'template-engine' => $template_engine
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme = new Theme(
      $params['theme-name'],
      $params['theme-uri'],
      $params['description'],
      $params['tags'],
      $params['version'],
      $params['author'],
      $params['author-uri'],
      $params['license'],
      $params['license-uri'],
      $params['text-domain'],
      $params['folder-name'],
      $params['template-engine']
    );
    $theme->install();
  }
  
  /**
   * Ask for theme's name
   * @return mixed
   */
  protected static function askForThemeName()
  {
    $theme_name = Dialog::getAnswer('Theme name (e.g. My Theme):');
    if (!$theme_name) {
      return self::askForThemeName();
    }
    return $theme_name;
  }
  
  /**
   * Asks for theme's URI
   * @return mixed
   */
  protected static function askForThemeURI()
  {
    return Dialog::getAnswer('Theme URI (e.g. http://my-theme.com):');
  }

  /**
   * Asks for theme's description
   * @return mixed
   */
  protected static function askForDescription()
  {
    return Dialog::getAnswer('Description:');
  }

  /**
   * Asks for theme's tags
   * @return mixed
   */
  protected static function askForTags()
  {
    return Dialog::getAnswer('Tags (e.g. modern, flat, simple, e-commerce):');
  }

  /**
   * Asks for theme's version
   * @return mixed
   */
  protected static function askForVersion()
  {
    return Dialog::getAnswer('Version [1.0]:', '1.0');
  }
  
  /**
   * Asks for theme's author
   * @return mixed
   */
  protected static function askForAuthor()
  {
    return Dialog::getAnswer('Author (e.g. John Doe):');
  }
  
  /**
   * Asks for theme's author URI
   * @return mixed
   */
  protected static function askForAuthorURI()
  {
    return Dialog::getAnswer('Author URI (e.g. http://john-doe.com):');
  }
  
  /**
   * Asks for theme's license
   * @return mixed
   */
  protected static function askForLicense()
  {
    return Dialog::getAnswer('License [GNU General Public License]:', 'GNU General Public License');
  }
  
  /**
   * Asks for theme's license URI
   * @return mixed
   */
  protected static function askForLicenseURI()
  {
    return Dialog::getAnswer('License URI [http://www.gnu.org/licenses/gpl-2.0.html]:', 'http://www.gnu.org/licenses/gpl-2.0.html');
  }
  
  /**
   * Asks for theme's text domain
   * @param $theme_name
   * @return mixed
   */
  protected static function askForTextDomain($theme_name)
  {
    $default = StringsManager::toKebabCase($theme_name);
    return Dialog::getAnswer("Text domain [$default]:", $default);
  }
  
  /**
   * Asks for theme's folder name
   * @param $theme_name
   * @return mixed
   */
  protected static function askForFolderName($theme_name)
  {
    $default = StringsManager::toKebabCase($theme_name);
    return Dialog::getAnswer("Folder name [$default]:", $default);
  }
  
  /**
   * Asks for theme's template engine
   * @return mixed
   */
  protected static function askForTemplateEngine()
  {
    $template_engines = [
      'Twig (Timber)' => 'timber',
      'PHP (Standard Wordpress)' => 'standard'
    ];
    $choice = Dialog::getChoice('Template engine:', array_keys($template_engines), null);
    return $template_engines[$choice];
  }
  
  /**
   * Checks params existence and normalizes them
   * @param $params
   * @return mixed
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['theme-name'] || !$params['text-domain'] || !$params['folder-name'] || !$params['template-engine']) {
      throw new \Exception('Error: unable to create theme because of missing parameters.');
    }
    
    // normalizing
    $theme_name = ucwords($params['theme-name']);
    $theme_uri = $params['theme-uri'];
    $description = ucfirst($params['description']);
    $tags = strtolower(StringsManager::removeMultipleSpaces($params['tags']));
    $version = $params['version'];
    $author = ucwords($params['author']);
    $author_uri = $params['author-uri'];
    $license = $params['license'];
    $license_uri = $params['license-uri'];
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $folder_name = StringsManager::toKebabCase($params['folder-name']);
    $template_engine = strtolower($params['template-engine']);
    
    if (!in_array($template_engine, self::TEMPLATE_ENGINES)) {
      throw new \Exception("Error: template engine '$template_engine' is not defined.");
    }
    
    return [
      'theme-name' => $theme_name,
      'theme-uri' => $theme_uri,
      'description' => $description,
      'tags' => $tags,
      'version' => $version,
      'author' => $author,
      'author-uri' => $author_uri,
      'license' => $license,
      'license-uri' => $license_uri,
      'text-domain' => $text_domain,
      'folder-name' => $folder_name,
      'template-engine' => $template_engine
    ];
  }
}
