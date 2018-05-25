<?php

/*=============*/
/* THEME MENUS */
/*=============*/
$menus = array(
    'primary_menu' => array(
        'default' => 'Menu',
        'description' => 'Menu principale',
        'object' => null
    )
);

/**
 * Creates menus
 */
function createMenus()
{
    global $menus;

    foreach ($menus as $location => $data) {
        // registering menu's location
        register_nav_menu($location, $data['description']);

        // getting locations
        $locations = get_nav_menu_locations();

        // creating menu if it doesn't exist or retrieving it otherwise
        if (empty($locations) || !array_key_exists($location, $locations) || $locations[$location] === null) {
            wp_create_nav_menu($data['default']);
            $menus[$location]['object'] = wp_get_nav_menu_object($data['default']);
        } else {
            $menus[$location]['object'] = wp_get_nav_menu_object($locations[$location]);
        }

        // forcing location/menu assignment
        $locations[$location] = $menus[$location]['object']->term_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

add_action('init', 'createMenus');

/**
 * Adds menus to twig global context
 *
 * @param $context
 * @return mixed
 */
function addMenusToContext($context)
{
    global $menus;

    foreach ($menus as $location => $data) {
        $context[$location] = new \Timber\Menu($data['object']->name);
    }

    return $context;
}

add_filter('timber/context', 'addMenusToContext');
