<?php

namespace Wordrobe\ThemeEntity;

/**
 * Class CustomPostType
 * @package Wordrobe\ThemeEntity
 */
class CustomPostType implements ThemeEntity {

	private $key;
	private $slug;
	private $taxonomies;
	private $generalName;
	private $singularName;
	private $description;
	private $textDomain;
	private $capabilityType;
	private $icon;

	/**
	 * CustomPostType constructor
	 * @param $key
	 * @param $generalName
	 * @param $singularName
	 * @param $textDomain
	 * @param string $capabilityType
	 * @param array $taxonomies
	 * @param string $icon
	 * @param bool $slug
	 * @param string $description
	 */
	function __construct($key, $generalName, $singularName, $textDomain, $capabilityType = 'post', $taxonomies = [], $icon = 'dashicons-admin-post', $slug = true, $description = '')
	{
		$this->key = $key;
		$this->generalName = $generalName;
		$this->singularName = $singularName;
		$this->textDomain = $textDomain;
		$this->capabilityType = $capabilityType;
		$this->taxonomies = ($capabilityType === 'post') ? $taxonomies : null;
		$this->icon = $icon;
		$this->slug = $slug;
		$this->description = $description;
		add_action('init', [$this, 'register'], 0);
	}

	/**
	 * Handles post type registration
	 */
	public function register() {
		$settings = $this->getSettings();
		register_post_type($this->key, $settings);
	}

	/**
	 * Returns all post type's supportable features according to capability type
	 * @return array
	 */
	private function getSupportableFeatures()
	{
		$supports = ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions'];
		if ($this->capabilityType === 'page') {
			$supports[] = 'page-attributes';
		} else {
			$supports[] = 'post-formats';
		}
		return $supports;
	}

	/**
	 * Returns post type's admin labels
	 * @return array
	 */
	private function getLabels()
	{
		return [
			'name'                  => _x($this->generalName, "$this->singularName General Name", $this->textDomain),
			'singular_name'         => _x($this->singularName, "$this->singularName Singular Name", $this->textDomain),
			'menu_name'             => __($this->generalName, $this->textDomain),
			'name_admin_bar'        => __($this->singularName, $this->textDomain),
			'archives'              => __("$this->singularName Archives", $this->textDomain),
			'attributes'            => __("$this->singularName Attributes", $this->textDomain),
			'parent_item_colon'     => __("Parent $this->singularName:", $this->textDomain),
			'all_items'             => __("All $this->generalName", $this->textDomain),
			'add_new_item'          => __("Add New $this->singularName", $this->textDomain),
			'add_new'               => __("Add New", $this->textDomain),
			'new_item'              => __("New $this->singularName", $this->textDomain),
			'edit_item'             => __("Edit $this->singularName", $this->textDomain),
			'update_item'           => __("Update $this->singularName", $this->textDomain),
			'view_item'             => __("View $this->singularName", $this->textDomain),
			'view_items'            => __("View $this->generalName", $this->textDomain),
			'search_items'          => __("Search $this->singularName", $this->textDomain),
			'not_found'             => __("Not $this->singularName found", $this->textDomain),
			'not_found_in_trash'    => __("Not $this->singularName found in Trash", $this->textDomain),
			'featured_image'        => __("Featured Image", $this->textDomain),
			'set_featured_image'    => __("Set featured image", $this->textDomain),
			'remove_featured_image' => __("Remove featured image", $this->textDomain),
			'use_featured_image'    => __("Use as featured image", $this->textDomain),
			'insert_into_item'      => __("Insert into $this->singularName", $this->textDomain),
			'uploaded_to_this_item' => __("Uploaded to this $this->singularName", $this->textDomain),
			'items_list'            => __("$this->generalName list", $this->textDomain),
			'items_list_navigation' => __("$this->generalName list navigation", $this->textDomain),
			'filter_items_list'     => __("Filter $this->generalName list", $this->textDomain),
		];
	}

	/**
	 * Returns post type's settings
	 * @return array
	 */
	private function getSettings()
	{
		return [
			'label'                 => __($this->generalName, $this->textDomain),
			'description'           => __($this->description, $this->textDomain),
			'labels'                => $this->getLabels(),
			'capability_type'       => $this->capabilityType,
			'hierarchical'			=> true,
			'taxonomies'			=> $this->taxonomies,
			'has_archive'			=> true,
			'supports'              => $this->getSupportableFeatures(),
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'				=> $this->icon,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'public'                => true,
			'publicly_queryable'    => true,
			'exclude_from_search'	=> false,
			'rewrite'				=> (gettype($this->slug) === 'boolean') ? $this->slug : ['slug' => $this->slug]
		];
	}
}