<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('templates/views/404.html.twig', $context);
