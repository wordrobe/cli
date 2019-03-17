<?php

use Wordrobe\Helper\Config;

/*==========*/
/* AUTOLOAD */
/*==========*/
$autoload = require(Config::getRootPath() . '/vendor/autoload.php');
$autoload->addPsr4('Example\\', __DIR__ . '/core');

/*===========*/
/* BOOTSTRAP */
/*===========*/
add_action('after_setup_theme', '\Example\Theme::init');
