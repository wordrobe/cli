<?php

Wordrobe\Feature\Factory::create('PostType', [
  'key' => 'event',
  'settings' => [
    'label' => __("Events", 'example'),
    'description' => __("", 'example'),
    'capability_type' => 'post',
    'hierarchical' => false,
    'has_archive' => true,
    'supports' => ["title", "editor", "author", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "post-formats", "page-attributes"],
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-post',
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => !true,
    'rewrite' => ['slug' => 'events', 'with_front' => false],
    'show_in_rest' => true,
    'labels' => [
      'name' => _x("Events", 'Event General Name', 'example'),
      'singular_name' => _x("Event", 'Event Singular Name', 'example'),
      'menu_name' => __("Events", 'example'),
      'name_admin_bar' => __("Event", 'example'),
      'archives' => __("Event Archives", 'example'),
      'attributes' => __("Event Attributes", 'example'),
      'parent_item_colon' => __("Parent Event:", 'example'),
      'all_items' => __("All Events", 'example'),
      'add_new_item' => __("Add New Event", 'example'),
      'add_new' => __("Add New", 'example'),
      'new_item' => __("New Event", 'example'),
      'edit_item' => __("Edit Event", 'example'),
      'update_item' => __("Update Event", 'example'),
      'view_item' => __("View Event", 'example'),
      'view_items' => __("View Events", 'example'),
      'search_items' => __("Search Event", 'example'),
      'not_found' => __("Not Event found", 'example'),
      'not_found_in_trash' => __("Not Event found in Trash", 'example'),
      'featured_image' => __("Featured Image", 'example'),
      'set_featured_image' => __("Set featured image", 'example'),
      'remove_featured_image' => __("Remove featured image", 'example'),
      'use_featured_image' => __("Use as featured image", 'example'),
      'insert_into_item' => __("Insert into Event", 'example'),
      'uploaded_to_this_item' => __("Uploaded to this Event", 'example'),
      'items_list' => __("Events list", 'example'),
      'items_list_navigation' => __("Events list navigation", 'example'),
      'filter_items_list' => __("Filter Events list", 'example')
    ]
  ]
]);