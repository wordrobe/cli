<?php

use Timber\Timber;

$context = Timber::get_context();
$context['authors'] = Timber::get_posts();

Timber::render('templates/default/author.html.twig', $context);
