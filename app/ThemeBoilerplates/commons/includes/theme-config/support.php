<?php

/*===============*/
/* THEME SUPPORT */
/*===============*/
function setThemeSupport() {
	add_theme_support('post-thumbnails');
	add_theme_support('custom-logo');
	add_theme_support('html5');
	add_theme_support('post-formats', array('gallery', 'link', 'image', 'quote', 'video', 'audio'));
}
add_action('init', 'setThemeSupport');
