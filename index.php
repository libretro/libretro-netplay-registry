<?php

/**
 * @file
 * Managed a registry.lpl file for libretro to use as a netplay host registry.
 */

// Let the browser know we will output plain text.
header('Content-Type: text/plain');

// See if we are adding an entry to the registry.
$entryProperties = array('username', 'corename', 'coreversion', 'gamename', 'gamecrc');
$addedEntry = array();

// Fill in all the properties.
foreach ($entryProperties as $property) {
	// Retrieve the given property.
	$value = isset($_GET[$property]) ? cleanProperty($_GET[$property]) : '';

	// If it's valid, then add it to the entry.
	if (!empty($value)) {
		$addedEntry[$property] = $value;
	}
	else {
		// The properties and invalid, ignore the new entry.
		$addedEntry = array();
		break;
	}
}

// If we're not adding an entry, output the registry.
if (empty($addedEntry)) {
	// Retrieve the registry.
	$registry = readRegistry();
	removeOldEntries($registry);
	echo saveRegistry($registry);
	exit;
}

// Fill in the remaining properties.
$addedEntry['ip'] = $_SERVER['REMOTE_ADDR'];
$addedEntry['time'] = time();
$addedEntry['port'] = '55435';
if (isset($_GET['port'])) {
	$port = cleanProperty($_GET['port']);
	if (!empty($port)) {
		$addedEntry['port'] = $port;
	}
}

/**
 * Read the registry file into an array of entries.
 */
function readRegistry() {
	// Build the registery.
	$registry = array();
	$currentEntry = array();
	$properties = array('username', 'ip', 'port', 'corename', 'coreversion', 'gamename', 'gamecrc', 'time');

	// Read through the registry.lpl file.
	$fn = fopen('registry.lpl', 'r');
	while(!feof($fn))  {
		$line = fgets($fn);
		// Fill in each property.
		foreach ($properties as $property) {
			if (!array_key_exists($property, $currentEntry)) {
				$currentEntry[$property] = trim($line);
				break 1;
			}
		}
		// If it has filled in the time, then it's done.
		if (isset($currentEntry['time'])) {
			array_push($registry, $currentEntry);
			$currentEntry = array();
		}
	}
	fclose($fn);
	return $registry;
}

/**
 * Save the given registry array into the registry file.
 *
 * @return A string representing the registry file.
 */
function saveRegistry($registry) {
	$output = array();
	foreach ($registry as $index => $entry) {
		if (!empty($entry['username'])) {
			array_push($output, implode(array(
				$entry['username'],
				$entry['ip'],
				$entry['port'],
				$entry['corename'],
				$entry['coreversion'],
				$entry['gamename'],
				$entry['gamecrc'],
				$entry['time'],
			), "\n"));
		}
	}
	$output = implode($output, "\n");
	file_put_contents('registry.lpl', $output);
	return $output;
}

/**
 * Cleans the given GET parameter.
 */
function cleanProperty($input = '') {
	return preg_replace('/[^-a-zA-Z0-9._ ]/', '', $input);
}

/**
 * Adds the entry, with a unique username, ip and port.
 */
function addEntry(&$registry, $newEntry) {
	$added = FALSE;
	foreach ($registry as $index => $entry) {
		// Throttle IP requests by 10 seconds.
		if ($newEntry['ip'] == $entry['ip'] && $newEntry['time'] - $entry['time'] < 10) {
			$added = TRUE;
			break;
		}

		// Update unique entries by username, IP and Port.
		if ($entry['username'] == $newEntry['username'] && $entry['ip'] == $newEntry['ip'] && $entry['port'] == $newEntry['port']) {
			$registry[$index] = $newEntry;
			$added = TRUE;
			break;
		}
	}

	// If the entry is still not present, add it.
	if (!$added) {
		array_push($registry, $newEntry);
	}
}

/**
 * Removes any old entries from the registry array.
 */
function removeOldEntries(&$registry) {
	foreach ($registry as $index => $entry) {
		if ($entry['time'] < time() - 300) {
			$registry[$index]['username'] = '';
		}
	}
}

// Retrieve the registry.
$registry = readRegistry();

// Add the entry to the registry.
addEntry($registry, $addedEntry);

// Remove any old entries from the registry.
removeOldEntries($registry);

// Save and output the new registry.
echo saveRegistry($registry);
