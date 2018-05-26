<?php

namespace Wordrobe\ThemeEntity;
use Wordrobe\Helper\StringsManager;

/**
 * Class Taxonomy
 * @package Wordrobe\ThemeEntity
 */
class Taxonomy implements ThemeEntity
{
    private $key;
    private $general_name;
    private $singular_name;
    private $text_domain;
    private $post_types;
    private $hierarchical;

    /**
     * Taxonomy constructor
     * @param $key
     * @param $general_name
     * @param $singular_name
     * @param $text_domain
     * @param $post_types
     * @param bool $hierarchical
     */
    public function __construct($key, $general_name, $singular_name, $text_domain, $post_types, $hierarchical = true)
    {
        $this->key = $key;
        $this->general_name = $general_name;
        $this->singular_name = $singular_name;
        $this->text_domain = $text_domain;
        $this->post_types = explode(',', StringsManager::removeSpaces($post_types));
        $this->hierarchical = $hierarchical;
        add_action('init', [$this, 'register'], 0);
    }

    /**
     * Handles taxonomy registration
     */
    public function register()
    {
        $settings = $this->getSettings();
        register_taxonomy($this->key, $this->post_types, $settings);
    }

	/**
	 * Taxonomy key getter
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Taxonomy post types getter
	 * @return string
	 */
	public function getPostTypes() {
		return $this->post_types;
	}

    /**
     * Returns taxonomy's admin labels
     * @return array
     */
    private function getLabels()
    {
        return [
            'name'                       => _x($this->general_name, "$this->singular_name General _name", $this->text_domain),
            'singular_name'              => _x($this->singular_name, "$this->singular_name Singular _name", $this->text_domain),
            'menu_name'                  => __($this->singular_name, $this->text_domain),
            'all_items'                  => __("All $this->general_name", $this->text_domain),
            'parent_item'                => __("Parent $this->singular_name", $this->text_domain),
            'parent_item_colon'          => __("Parent $this->singular_name:", $this->text_domain),
            'new_item_name'              => __("New $this->singular_name", $this->text_domain),
            'add_new_item'               => __("Add New $this->singular_name", $this->text_domain),
            'edit_item'                  => __("Edit $this->singular_name", $this->text_domain),
            'update_item'                => __("Update $this->singular_name", $this->text_domain),
            'view_item'                  => __("View $this->singular_name", $this->text_domain),
            'separate_items_with_commas' => __("Separate $this->general_name with commas", $this->text_domain),
            'add_or_remove_items'        => __("Add or remove $this->general_name", $this->text_domain),
            'choose_from_most_used'      => __("Choose from the most used", $this->text_domain),
            'popular_items'              => __("Popular $this->general_name", $this->text_domain),
            'search_items'               => __("Search $this->general_name", $this->text_domain),
            'not_found'                  => __("Not Found", $this->text_domain),
            'no_terms'                   => __("No $this->general_name", $this->text_domain),
            'items_list'                 => __("$this->general_name list", $this->text_domain),
            'items_list_navigation'      => __("$this->general_name list navigation", $this->text_domain)
        ];
    }

    /**
     * Returns taxonomy's settings
     * @return array
     */
    private function getSettings()
    {
        return [
            'labels'			=> $this->getLabels(),
            'hierarchical'		=> $this->hierarchical,
            'public'			=> true,
            'show_ui'			=> true,
            'show_admin_column'	=> true,
            'show_in_nav_menus'	=> true,
            'show_tagcloud'		=> true
        ];
    }
}
