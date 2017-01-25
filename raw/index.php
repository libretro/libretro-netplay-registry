<?php

use RobLoach\LibretroNetplayRegistry\PlaylistFormatter;
use RobLoach\LibretroNetplayRegistry\Registry;
use RobLoach\LibretroNetplayRegistry\InputParser;

// Set the content type.
header('Content-Type: text/plain');

// Load the classes.
require_once __DIR__ . '/../src/autoload.php';

// Load the registry.
$registry = new Registry('../.registry');

// Check if we are adding a new entry.
$input = new InputParser();
$newEntry = $input->getEntry();
if ($newEntry) {
    $registry->insert($newEntry);
}

// Output the new playlist.
$playlist = new PlaylistFormatter($registry);
echo $playlist;
