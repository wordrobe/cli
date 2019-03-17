<?php

use Example\Theme;
use Timber\Timber;

$context = Theme::getContext();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_send_json($context);
}

Timber::render('templates/views/404.html.twig', $context);
