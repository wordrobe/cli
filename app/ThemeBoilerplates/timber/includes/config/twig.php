<?php

use Timber\Timber;

/*========*/
/* TIMBER */
/*========*/
$timber = new Timber();

/*============*/
/* EXTENSIONS */
/*============*/
function addStringLoaderExtension($twig)
{
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
}

add_filter('timber/twig', 'addStringLoaderExtension');

/*=============*/
/* GLOBAL VARS */
/*=============*/
function setTwigGlobalVars($context)
{
    $context['env'] = defined('WP_ENV') ? WP_ENV : 'production';
    $context['ajax_url'] =  site_url() . '/wp-admin/admin-ajax.php';
    return $context;
}

add_filter('timber/context', 'setTwigGlobalVars');
