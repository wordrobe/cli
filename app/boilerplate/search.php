<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('templates/default/search.html.twig', $context);
