<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('views/default/search.html.twig', $context);
