<?php

namespace RobLoach\LibretroNetplayRegistry;

require_once __DIR__ . '/Registry.php';

use RobLoach\LibretroNetplayRegistry\Registry;

class PlaylistFormatter
{
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function __toString()
    {
        $output = array();
        foreach ($this->registry->selectAll() as $index => $entry) {
            $properties = array(
                $entry['username'],
                $entry['ip'],
                $entry['port'],
                $entry['corename'],
                $entry['coreversion'],
                $entry['gamename'],
                $entry['gamecrc'],
                $entry['haspassword'] ? '1' : '0',
                $entry['created'],
            );
            array_push($output, implode($properties, "\n"));
        }
        return implode($output, "\n");
    }
}
