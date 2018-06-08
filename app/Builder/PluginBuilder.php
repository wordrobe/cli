<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Dialog;
use Wordrobe\Wrapper\Plugin;
use Wordrobe\Helper\StringsManager;

/**
 * Class PluginBuilder
 * @package Wordrobe\Builder
 */
class PluginBuilder implements Builder
{
  /**
   * Handles plugin creation wizard
   */
  public static function startWizard()
  {
    try {
      $plugin_name = self::askForPluginName();
      $plugin_uri = self::askForPluginURI();
      $description = self::askForDescription();
      $version = self::askForVersion();
      $author = self::askForAuthor();
      $author_uri = self::askForAuthorURI();
      $license = self::askForLicense();
      $license_uri = self::askForLicenseURI();
      $text_domain = self::askForTextDomain($plugin_name);
      $folder_name = self::askForFolderName($plugin_name);
      self::build([
        'plugin-name' => $plugin_name,
        'plugin-uri' => $plugin_uri,
        'author' => $author,
        'author-uri' => $author_uri,
        'description' => $description,
        'version' => $version,
        'license' => $license,
        'license-uri' => $license_uri,
        'text-domain' => $text_domain,
        'folder-name' => $folder_name,
      ]);
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }

    Dialog::write('Plugin installed!', 'green');
  }

  /**
   * Builds plugin
   * @param array $params
   * @example PluginBuilder::create([
   *  'plugin-name' => $plugin_name,
   *  'plugin-uri' => $plugin_uri,
   *  'author' => $author,
   *  'author-uri' => $author_uri,
   *  'description' => $description,
   *  'version' => $version,
   *  'license' => $license,
   *  'license-uri' => $license_uri,
   *  'text-domain' => $text_domain,
   *  'folder-name' => $folder_name,
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::checkParams($params);
    $plugin = new Plugin(
      $params['plugin-name'],
      $params['plugin-uri'],
      $params['author'],
      $params['author-uri'],
      $params['description'],
      $params['version'],
      $params['license'],
      $params['license-uri'],
      $params['text-domain'],
      $params['folder-name']
    );
    $plugin->install();
  }

  /**
   * Ask for plugin's name
   * @return mixed
   */
  protected static function askForPluginName()
  {
    $plugin_name = Dialog::getAnswer('Plugin name (e.g. My Plugin):');
    return $plugin_name ?: self::askForPluginName();
  }

  /**
   * Asks for plugin's URI
   * @return mixed
   */
  protected static function askForPluginURI()
  {
    return Dialog::getAnswer('Plugin URI (e.g. http://my-plugin.com):');
  }

  /**
   * Asks for plugin's description
   * @return mixed
   */
  protected static function askForDescription()
  {
    return Dialog::getAnswer('Description:');
  }

  /**
   * Asks for plugin's version
   * @return mixed
   */
  protected static function askForVersion()
  {
    return Dialog::getAnswer('Version [1.0]:', '1.0');
  }

  /**
   * Asks for plugin's author
   * @return mixed
   */
  protected static function askForAuthor()
  {
    return Dialog::getAnswer('Author (e.g. John Doe):');
  }

  /**
   * Asks for plugin's author URI
   * @return mixed
   */
  protected static function askForAuthorURI()
  {
    return Dialog::getAnswer('Author URI (e.g. http://john-doe.com):');
  }

  /**
   * Asks for plugin's license
   * @return mixed
   */
  protected static function askForLicense()
  {
    return Dialog::getAnswer('License [GNU General Public License]:', 'GNU General Public License');
  }

  /**
   * Asks for plugin's license URI
   * @return mixed
   */
  protected static function askForLicenseURI()
  {
    return Dialog::getAnswer('License URI [http://www.gnu.org/licenses/gpl-2.0.html]:', 'http://www.gnu.org/licenses/gpl-2.0.html');
  }

  /**
   * Asks for plugin's text domain
   * @param $plugin_name
   * @return mixed
   */
  protected static function askForTextDomain($plugin_name)
  {
    $default = StringsManager::toKebabCase($plugin_name);
    return Dialog::getAnswer("Text domain [$default]:", $default);
  }

  /**
   * Asks for plugin's folder name
   * @param $plugin_name
   * @return mixed
   */
  protected static function askForFolderName($plugin_name)
  {
    $default = StringsManager::toKebabCase($plugin_name);
    return Dialog::getAnswer("Folder name [$default]:", $default);
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
    if (!$params['plugin-name'] || !$params['text-domain'] || !$params['folder-name']) {
      throw new \Exception('Error: unable to create plugin because of missing parameters.');
    }

    // normalizing
    $plugin_name = ucwords($params['plugin-name']);
    $plugin_uri = $params['plugin-uri'];
    $author = ucwords($params['author']);
    $author_uri = $params['author-uri'];
    $description = ucfirst($params['description']);
    $version = $params['version'];
    $license = $params['license'];
    $license_uri = $params['license-uri'];
    $text_domain = StringsManager::toKebabCase($params['text-domain']);
    $folder_name = StringsManager::toKebabCase($params['folder-name']);

    return [
      'plugin-name' => $plugin_name,
      'plugin-uri' => $plugin_uri,
      'author' => $author,
      'author-uri' => $author_uri,
      'description' => $description,
      'version' => $version,
      'license' => $license,
      'license-uri' => $license_uri,
      'text-domain' => $text_domain,
      'folder-name' => $folder_name
    ];
  }
}
