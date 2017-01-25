<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/autoload.php');

class PlaylistFormatterTest extends TestBase
{
    function testToString() {
        $entry = $this->randomEntry();
        $this->registry->insert($entry, false);

        $entries = $this->registry->selectAll();
        $entry = $entries[0];
        $output = "{$entry['username']}\n{$entry['ip']}\n{$entry['port']}\n{$entry['corename']}\n{$entry['coreversion']}\n{$entry['gamename']}\n{$entry['gamecrc']}\n{$entry['haspassword']}\n{$entry['created']}";
        $playlist = new PlaylistFormatter($this->registry);
        $this->assertEquals($output, (string)$playlist);
    }
}