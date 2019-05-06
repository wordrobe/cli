<?php

use Example\Theme;
use Example\Repository\Repository;
use Timber\Timber;

$context = Theme::getContext();
$context['data'] = Repository::getAllFormattedData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_send_json($context);
}

Timber::render('templates/views/category.html.twig', $context);
