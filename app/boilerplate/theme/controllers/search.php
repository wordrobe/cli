<?php

use Timber\Timber;

$context = Timber::get_context();
$context['results'] = Timber::get_posts();

Timber::render('templates/views/search.html.twig', $context);
