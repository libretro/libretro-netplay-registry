<?php

namespace RobLoach\LibretroNetplayRegistry;

use RobLoach\LibretroNetplayRegistry\Registry;

/**
 * Class PlaylistFormatter.
 */
class PlaylistFormatter
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * PlaylistFormatter constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return string
     */
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
                //$entry['haspassword'] ? '1' : '0',
                //$entry['connectable'] ? '1' : '0',
                $entry['created'],
            );
            array_push($output, implode($properties, "\n"));
        }
        return implode($output, "\n");
    }
}
