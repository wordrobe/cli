<?php

use Timber\Timber;

$context = Timber::get_context();
$context['posts'] = Timber::get_posts();

Timber::render('templates/default/search.html.twig', $context);