<?php

use Example\Theme;
use Example\Repository\EventRepository;
use Timber\Timber;

$context = Theme::getContext();
$context['data'] = EventRepository::getAllFormattedData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_send_json($context);
}

Timber::render('templates/views/archive-event.html.twig', $context));
