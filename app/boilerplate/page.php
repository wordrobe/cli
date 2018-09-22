<?php

use Timber\Timber;
use Timber\Post;

$context = Timber::get_context();
$context['page'] = new Post();

Timber::render('templates/default/page.html.twig', $context);
