<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('views/default/archive.html.twig', $context);
