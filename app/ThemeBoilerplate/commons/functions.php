<?php

/*===================*/
/* CUSTOM POST TYPES */
/*===================*/
foreach(glob(get_template_directory() . '/includes/custom-post-types/*.php') as $postType) {
	require_once $postType;
}


/*===================*/
/* CUSTOM TAXONOMIES */
/*===================*/
foreach(glob(get_template_directory() . '/includes/custom-taxonomies/*.php') as $taxonomy) {
	require_once $taxonomy;
}


/*===========*/
/* UTILITIES */
/*===========*/
foreach(glob(get_template_directory() . '/includes/utils/*.php') as $utility) {
	require_once $utility;
}


/*==========*/
/* SERVICES */
/*==========*/
foreach(glob(get_template_directory() . '/includes/services/*.php') as $service) {
	require_once $service;
}


/*===============*/
/* CUSTOM FIELDS */
/*===============*/
foreach(glob(get_template_directory() . '/includes/custom-fields/*.php') as $customField) {
	require_once $customField;
}


/*==============*/
/* THEME CONFIG */
/*==============*/
foreach(glob(get_template_directory() . '/includes/theme-config/*.php') as $config) {
	require_once $config;
}


/*============*/
/* SHORTCODES */
/*============*/
foreach(glob(get_template_directory() . '/includes/shortcodes/**/*.php') as $shortcode) {
	require_once $shortcode;
}