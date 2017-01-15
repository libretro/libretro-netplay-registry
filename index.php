<?php

/**
 * @file
 * Managed a registry.lpl file for libretro to use as a netplay host registry.
 */

// See if we are adding an entry to the registry.
$entryProperties = array('username', 'corename', 'coreversion', 'gamename', 'gamecrc');
$addedEntry = array();

// Fill in all the properties.
foreach ($entryProperties as $property) {
	if (isset($_GET[$property])) {
		$addedEntry[$property] = $_GET[$property];
	}
	else {
		$addedEntry = array();
		break;
	}
}

// If we're not adding an entry, output the registry.
if (empty($addedEntry)) {
	header('Content-Type: text/plain');
	//header('Content-Disposition: attachment;filename=registry.lpl');

	// Retrieve the registry.
	$registry = readRegistry();
	removeOldEntries($registry);
	echo saveRegistry($registry);
	exit;
}

// Fill in the optional properties.
$addedEntry['ip'] = $_GET['ip'] ? $_GET['ip'] : $_SERVER['REMOTE_ADDR'];
$addedEntry['port'] = $_GET['port'] ? $_GET['port'] : '55435';

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

// Make sure the entry has the current time.
$addedEntry['time'] = time();

// Add the entry to the registry.
array_push($registry, $addedEntry);

// Remove any old entries from the registry.
removeOldEntries($registry);

// Save and output the new registry.
header('Content-Type: text/plain');
echo saveRegistry($registry);
