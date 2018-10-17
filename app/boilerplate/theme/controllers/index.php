<?php

use Timber\Timber;

$context = Timber::get_context();

Timber::render('templates/views/index.html.twig', $context);
