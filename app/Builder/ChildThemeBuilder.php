<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Entity\ChildTheme;

/**
 * Class ChildThemeBuilder
 * @package Wordrobe\Builder
 */
class ChildThemeBuilder extends ThemeBuilder
{
  /**
   * Handles child theme creation wizard
   */
  public static function startWizard()
  {
    try {
      $theme_name = parent::askForThemeName();
      $theme_uri = parent::askForThemeURI();
      $author = parent::askForAuthor();
      $author_uri = parent::askForAuthorURI();
      $description = parent::askForDescription();
      $version = parent::askForVersion();
      $license = parent::askForLicense();
      $license_uri = parent::askForLicenseURI();
      $text_domain = parent::askForTextDomain($theme_name);
      $tags = parent::askForTags();
      $folder_name = parent::askForFolderName($theme_name);
      $parent = self::askForParentTheme();
      self::build([
        'theme-name' => $theme_name,
        'theme-uri' => $theme_uri,
        'author' => $author,
        'author-uri' => $author_uri,
        'description' => $description,
        'version' => $version,
        'license' => $license,
        'license-uri' => $license_uri,
        'text-domain' => $text_domain,
        'tags' => $tags,
        'folder-name' => $folder_name,
        'parent' => $parent
      ]);
      Dialog::write('Child theme installed!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }
  
  /**
   * Builds child theme
   * @param array $params
   * @example ChildThemeBuilder::create([
   *  'theme-name' => $theme_name,
   *  'theme-uri' => $theme_uri,
   *  'author' => $author,
   *  'author-uri' => $author_uri,
   *  'description' => $description,
   *  'version' => $version,
   *  'license' => $license,
   *  'license-uri' => $license_uri,
   *  'text-domain' => $text_domain,
   *  'tags' => $tags,
   *  'folder-name' => $folder_name,
   *  'parent' => $parent
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $theme = new ChildTheme(
      $params['theme-name'],
      $params['theme-uri'],
      $params['author'],
      $params['author-uri'],
      $params['description'],
      $params['version'],
      $params['license'],
      $params['license-uri'],
      $params['text-domain'],
      $params['tags'],
      $params['folder-name'],
      $params['parent']
    );
    $theme->install();
  }
  
  /**
   * Asks for child theme's parent
   * @return mixed
   */
  protected static function askForParentTheme()
  {
    $themes = Config::get('themes', true);
    return Dialog::getChoice('Parent theme:', array_keys($themes), null);
  }
  
  /**
   * Checks params existence and normalizes them
   * @param $params
   * @return array
   * @throws \Exception
   */
  private static function checkParams($params)
  {
    // checking existence
    if (!$params['theme-name'] || !$params['text-domain'] || !$params['folder-name'] || !$params['parent']) {
      throw new \Exception('Error: unable to create child theme because of missing parameters.');
    }
    
    // normalizing
    $theme_name = ucwords($params['theme-name']);
    $theme_uri = $params['theme-uri'];
    $author = ucwords($params['author']);
    $author_uri = $params['author-uri'];
    $description = ucfirst($params['description']);
    $version = $params['version'];
    $license = $params['license'];
    $license_uri = $params['license-uri'];
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $tags = strtolower(StringsManager::removeMultipleSpaces($params['tags']));
    $folder_name = StringsManager::toKebabCase($params['folder-name']);
    $parent = StringsManager::toKebabCase($params['parent']);
    
    Config::check("themes.$parent", 'array', "Error: parent theme '$parent' not found.");
    
    return [
      'theme-name' => $theme_name,
      'theme-uri' => $theme_uri,
      'author' => $author,
      'author-uri' => $author_uri,
      'description' => $description,
      'version' => $version,
      'license' => $license,
      'license-uri' => $license_uri,
      'text-domain' => $text_domain,
      'tags' => $tags,
      'folder-name' => $folder_name,
      'parent' => $parent
    ];
  }
}
