<?php

use RobLoach\LibretroNetplayRegistry\PlaylistFormatter;
use RobLoach\LibretroNetplayRegistry\Registry;

// Set the content type.
header('Content-Type: text/plain');

// Load the classes.
require_once __DIR__ . '/../src/autoload.php';

// Load the registry.
$registry = new Registry('../.registry');

// Check if we are adding a new entry.
$newEntry = getNewEntry();
if ($newEntry) {
    $registry->insert($newEntry);
}

// Output the new playlist.
$playlist = new PlaylistFormatter($registry);
echo $playlist;

/**
 * Reads the GET parameters to load a new entry.
 */
function getNewEntry()
{
    $entryProperties = array('username', 'corename', 'coreversion', 'gamename', 'gamecrc');
    $addedEntry = array();
    // Fill in all the properties.
    foreach ($entryProperties as $property) {
        // Retrieve the given property.
        $value = isset($_GET[$property]) ? cleanProperty($_GET[$property]) : '';
        // If it's valid, then add it to the entry.
        if (!empty($value) || $value === '0') {
            $addedEntry[$property] = $value;
        } else {
            // The properties and invalid, ignore the new entry.
            $addedEntry = array();
            break;
        }
    }

    if (empty($addedEntry)) {
        return null;
    }
    $addedEntry['ip'] = $_SERVER['REMOTE_ADDR'];
    $addedEntry['time'] = time();
    $addedEntry['port'] = '55435';
    if (isset($_GET['port'])) {
        $port = cleanProperty($_GET['port']);
        if (!empty($port)) {
            $addedEntry['port'] = $port;
        }
    }
    if (isset($_GET['haspassword'])) {
        $addedEntry['haspassword'] = empty($_GET['haspassword']);
    }

    return $addedEntry;
}

/**
 * Cleans the given GET parameter.
 */
function cleanProperty($input = '')
{
    return preg_replace('/[^-a-zA-Z0-9-()[]!,&\'._ ]/', '', $input);
}
