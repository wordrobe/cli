<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class CustomTaxonomy
 * @package Wordrobe\ThemeEntity
 */
class CustomTaxonomy implements ThemeEntity {

	private $key;
	private $generalName;
	private $singularName;
	private $textDomain;
	private $postTypes;
	private $hierarchical;

	/**
	 * CustomTaxonomy constructor
	 *
	 * @param $key
	 * @param $generalName
	 * @param $singularName
	 * @param $textDomain
	 * @param $postTypes
	 * @param bool $hierarchical
	 */
	public function __construct($key, $generalName, $singularName, $textDomain, $postTypes, $hierarchical = true)
	{
		$this->key = $key;
		$this->generalName = $generalName;
		$this->singularName = $singularName;
		$this->textDomain = $textDomain;
		$this->postTypes = $postTypes;
		$this->hierarchical = $hierarchical;
		add_action('init', [$this, 'register'], 0);
	}

	/**
	 * Handles taxonomy registration
	 */
	public function register() {
		$settings = $this->getSettings();
		register_taxonomy($this->key, $this->postTypes, $settings);
	}

	/**
	 * Returns taxonomy's admin labels
	 *
	 * @return array
	 */
	private function getLabels()
	{
		return [
			'name'                       => _x($this->generalName, "$this->singularName General Name", $this->textDomain),
			'singular_name'              => _x($this->singularName, "$this->singularName Singular Name", $this->textDomain),
			'menu_name'                  => __($this->singularName, $this->textDomain),
			'all_items'                  => __("All $this->generalName", $this->textDomain),
			'parent_item'                => __("Parent $this->singularName", $this->textDomain),
			'parent_item_colon'          => __("Parent $this->singularName:", $this->textDomain),
			'new_item_name'              => __("New $this->singularName", $this->textDomain),
			'add_new_item'               => __("Add New $this->singularName", $this->textDomain),
			'edit_item'                  => __("Edit $this->singularName", $this->textDomain),
			'update_item'                => __("Update $this->singularName", $this->textDomain),
			'view_item'                  => __("View $this->singularName", $this->textDomain),
			'separate_items_with_commas' => __("Separate $this->generalName with commas", $this->textDomain),
			'add_or_remove_items'        => __("Add or remove $this->generalName", $this->textDomain),
			'choose_from_most_used'      => __("Choose from the most used", $this->textDomain),
			'popular_items'              => __("Popular $this->generalName", $this->textDomain),
			'search_items'               => __("Search $this->generalName", $this->textDomain),
			'not_found'                  => __("Not Found", $this->textDomain),
			'no_terms'                   => __("No $this->generalName", $this->textDomain),
			'items_list'                 => __("$this->generalName list", $this->textDomain),
			'items_list_navigation'      => __("$this->generalName list navigation", $this->textDomain)
		];
	}

	/**
	 * Returns taxonomy's settings
	 *
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
