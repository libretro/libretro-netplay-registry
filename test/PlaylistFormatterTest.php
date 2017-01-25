<?php

namespace RobLoach\LibretroNetplayRegistry\Test;

use PHPUnit\Framework\TestCase;
use RobLoach\LibretroNetplayRegistry\PlaylistFormatter;

require_once __DIR__ . '/TestBase.php';
require_once __DIR__ . '/../src/autoload.php';

class PlaylistFormatterTest extends TestBase
{
    public function testToString()
    {
        $entry = $this->randomEntry();
        $this->registry->insert($entry, false);

        $entries = $this->registry->selectAll();
        $entry = $entries[0];
        $properties = array(
            $entry['username'],
            $entry['ip'],
            $entry['port'],
            $entry['corename'],
            $entry['coreversion'],
            $entry['gamename'],
            $entry['gamecrc'],
            $entry['haspassword'],
            $entry['created']
        );
        $output = implode($properties, "\n");
        $playlist = new PlaylistFormatter($this->registry);
        $this->assertEquals($output, (string)$playlist);
    }
}
