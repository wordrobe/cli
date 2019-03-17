<?php

namespace Wordrobe\Entity;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Builder\EntityBuilder;
use Wordrobe\Builder\DTOBuilder;
use Wordrobe\Builder\RepositoryBuilder;
use Wordrobe\Builder\ArchiveBuilder;
use Wordrobe\Builder\SingleBuilder;
use Wordrobe\Builder\BlankTemplateBuilder;

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
      $this->updateConfig();
      $this->copyBoilerplate();
      $this->addThemeClass();
      $this->addHelpers();
      $this->addBasicFramework();
      $this->addDefaultTemplates();
      $this->addFunctions();
      $this->addStylesheet();
    } else {

      if (is_null(Config::get("themes.$this->folder_name"))) {
        $this->updateConfig();
      }

      throw new \Exception('Theme installation aborted.');
    }
  }

  /**
   * Adds Theme.php to theme
   * @throws \Exception
   */
  protected function addThemeClass()
  {
    $theme = new Template(
      "$this->path/core",
      'theme-class',
      [
        '{NAMESPACE}' => $this->namespace,
        '{TEXT_DOMAIN}' => $this->text_domain
      ]
    );
    $theme->save('Theme.php', 'force');
  }

  /**
   * Adds helpers to theme
   * @throws \Exception
   */
  protected function addHelpers()
  {
    $features_loader = new Template(
      "$this->path/core/Helper",
      'features-loader',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );

    $router = new Template(
      "$this->path/core/Helper",
      'router',
      [
        '{NAMESPACE}' => $this->namespace,
        '{TEXT_DOMAIN}' => $this->text_domain
      ]
    );

    $navigation = new Template(
      "$this->path/core/Helper",
      'navigation-helper',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );

    $acf = new Template(
      "$this->path/core/Helper",
      'acf-helper',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );

    $wpml = new Template(
      "$this->path/core/Helper",
      'wpml-helper',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );

    $tinymce = new Template(
      "$this->path/core/Helper",
      'tinymce-helper',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );

    $features_loader->save('FeaturesLoader.php', 'force');
    $router->save('Router.php', 'force');
    $navigation->save('Navigation.php', 'force');
    $acf->save('ACF.php', 'force');
    $wpml->save('WPML.php', 'force');
    $tinymce->save('TinyMCE.php', 'force');
  }

  /**
   * Adds basic entities, dtos and repositories to theme
   * @throws \Exception
   */
  protected function addBasicFramework()
  {
    EntityBuilder::build([
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    DTOBuilder::build([
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    RepositoryBuilder::build([
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);
  }

  /**
   * Adds default templates to theme
   * @throws \Exception
   */
  protected function addDefaultTemplates()
  {
    // index
    BlankTemplateBuilder::build([
      'filename' => 'index',
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    // 404
    BlankTemplateBuilder::build([
      'filename' => '404',
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    // single post
    SingleBuilder::build([
      'post-type' => 'post',
      'entity-name' => '',
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    // single page
    SingleBuilder::build([
      'post-type' => 'page',
      'entity-name' => '',
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    // posts archive
    ArchiveBuilder::build([
      'post-type' => 'post',
      'theme' => $this->folder_name,
      'override' => 'force'
    ]);

    // category archive
    ArchiveBuilder::build([
      'post-type' => 'post',
      'theme' => $this->folder_name,
      'filename' => 'category',
      'override' => 'force'
    ]);

    // tag archive
    ArchiveBuilder::build([
      'post-type' => 'post',
      'theme' => $this->folder_name,
      'filename' => 'tag',
      'override' => 'force'
    ]);

    // author archive
    ArchiveBuilder::build([
      'post-type' => 'post',
      'theme' => $this->folder_name,
      'filename' => 'author',
      'override' => 'force'
    ]);

    // search archive
    ArchiveBuilder::build([
      'post-type' => 'post',
      'theme' => $this->folder_name,
      'filename' => 'search',
      'override' => 'force'
    ]);
  }

  /**
   * Adds functions.php to theme
   * @throws \Exception
   */
  protected function addFunctions()
  {
    $functions = new Template(
      $this->path,
      'theme-functions',
      [
        '{NAMESPACE}' => $this->namespace
      ]
    );
    $functions->save('functions.php', 'force');
  }

  /**
   * Adds style.css to theme
   * @throws \Exception
   */
  protected function addStylesheet()
  {
    $stylesheet = new Template(
      $this->path,
      'theme-stylesheet',
      [
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
      ]
    );
    $stylesheet->save('style.css', 'force');
  }

  /**
   * Adds theme params to Config
   * @throws \Exception
   */
  protected function updateConfig()
  {
    $themeConfig = new Template(
      null,
      'config-theme',
      [
        '{NAMESPACE}' => $this->namespace,
        '{TEXT_DOMAIN}' => $this->text_domain
      ]
    );
    $content = $themeConfig->getContent();
    Config::set("themes.$this->folder_name", json_decode($content));
  }

  /**
   * Copies theme boilerplate
   * @throws \Exception
   */
  private function copyBoilerplate()
  {
    $boilerplatePath = dirname(__DIR__) . '/boilerplate/theme';
    FilesManager::copyFiles($boilerplatePath, $this->path);
    FilesManager::deleteFile(dirname($this->path) . '/.gitkeep');
  }
}
