<?php

namespace Wordrobe\Entity;

use Wordrobe\Config;

/**
 * Class ChildTheme
 * @package Wordrobe\Entity
 */
class ChildTheme extends Theme
{
    private $parent;

    /**
     * ChildTheme constructor.
     * @param $theme_name
     * @param $theme_uri
     * @param $author
     * @param $author_uri
     * @param $description
     * @param $version
     * @param $license
     * @param $license_uri
     * @param $text_domain
     * @param $tags
     * @param $folder_name
     * @param $parent
     */
    public function __construct($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $parent)
    {
        $this->parent = $parent;
        $template_engine = Config::get("themes.$this->parent.template_engine");
        parent::__construct($theme_name, $theme_uri, $author, $author_uri, $description, $version, $license, $license_uri, $text_domain, $tags, $folder_name, $template_engine);
    }

    /**
     * Adds style.css to theme
     */
    protected function addStylesheet()
    {
        $stylesheet = new Template('child-theme-stylesheet', [
            '{THEME_NAME}' => $this->theme_name,
            '{THEME_URI}' => $this->theme_uri,
            '{AUTHOR}' => $this->author,
            '{AUTHOR_URI}' => $this->author_uri,
            '{DESCRIPTION}' => $this->description,
            '{VERSION}' => $this->version,
            '{LICENSE}' => $this->license,
            '{LICENSE_URI}' => $this->license_uri,
            '{TEXT_DOMAIN}' => $this->text_domain,
            '{TAGS}' => $this->tags,
            '{PARENT_THEME}' => $this->parent
        ]);
        $stylesheet->save("$this->path/style.css");
    }

    /**
     * Adds theme params to Config
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
