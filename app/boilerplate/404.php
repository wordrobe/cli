<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('templates/default/404.html.twig', $context);
