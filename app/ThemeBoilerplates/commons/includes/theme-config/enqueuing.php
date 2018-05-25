<?php

/*==================*/
/* ENQUEUING ASSETS */
/*==================*/
function enqueueThemeAssets()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/main.css');
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/main.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueueThemeAssets');
