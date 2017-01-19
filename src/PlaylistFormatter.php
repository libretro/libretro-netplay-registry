<?php

require_once(__DIR__ . '/Registry.php');

class PlaylistFormatter {
	private $registry;

	function __construct(Registry $registry) {
		$this->registry = $registry;
	}

	function __toString() {
		$output = array();
		foreach ($this->registry->selectAll() as $index => $entry) {
			array_push($output, implode(array(
				$entry['username'],
				$entry['ip'],
				$entry['port'],
				$entry['corename'],
				$entry['coreversion'],
				$entry['gamename'],
				$entry['gamecrc'],
			), "\n"));
		}
		return implode($output, "\n");
	}
}
