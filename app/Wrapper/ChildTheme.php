<?php

namespace Wordrobe\Wrapper;

use Wordrobe\Config;

/**
 * Class ChildTheme
 * @package Wordrobe\Wrapper
 */
class ChildTheme extends Theme
{
  private $parent;
  
  /**
   * ChildTheme constructor.
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
   * @param $parent
   * @throws \Exception
   */
  public function __construct($theme_name, $theme_uri, $description, $tags, $version, $author, $author_uri, $license, $license_uri, $text_domain, $folder_name, $parent)
  {
    $this->parent = $parent;
    $template_engine = Config::get("themes.$this->parent.template-engine", true);
    parent::__construct($theme_name, $theme_uri, $description, $tags, $version, $author, $author_uri, $license, $license_uri, $text_domain, $folder_name, $template_engine);
  }
  
  /**
   * Adds style.css to theme
   * @throws \Exception
   */
  protected function addStylesheet()
  {
    $stylesheet = new Template('child-theme-stylesheet', [
      '{THEME_NAME}' => $this->theme_name,
      '{THEME_URI}' => $this->theme_uri,
      '{DESCRIPTION}' => $this->description,
      '{TAGS}' => $this->tags,
      '{VERSION}' => $this->version,
      '{AUTHOR}' => $this->author,
      '{AUTHOR_URI}' => $this->author_uri,
      '{LICENSE}' => $this->license,
      '{LICENSE_URI}' => $this->license_uri,
      '{TEXT_DOMAIN}' => $this->text_domain,
      '{PARENT_THEME}' => $this->parent
    ]);
    $stylesheet->save("$this->path/style.css");
  }
  
  /**
   * Adds theme params to Config
   * @throws \Exception
   */
  protected function updateConfig()
  {
    $themeConfig = new Template('child-theme-config', [
      '{TEMPLATE_ENGINE}' => $this->template_engine,
      '{PARENT_THEME}' => $this->parent
    ]);
    Config::set("themes.$this->folder_name", json_decode($themeConfig->getContent()));
  }
}
