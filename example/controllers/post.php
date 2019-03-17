<?php

use Example\Theme;
use Example\Repository\Repository;
use Timber\Timber;

$context = Theme::getContext();
$context['data'] = Repository::getFormattedData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_send_json($context);
}

Timber::render('templates/views/post.html.twig', $context);
