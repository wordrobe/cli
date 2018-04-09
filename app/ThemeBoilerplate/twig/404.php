<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('views/default/404.html.twig', $context);
