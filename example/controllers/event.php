<?php

use Example\Theme;
use Example\Repository\EventRepository;
use Timber\Timber;

$context = Theme::getContext();
$context['data'] = EventRepository::getFormattedData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_send_json($context);
}

Timber::render('templates/views/event.html.twig', $context);
