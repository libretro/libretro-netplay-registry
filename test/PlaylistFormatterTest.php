<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/autoload.php');

class PlaylistFormatterTest extends TestBase
{
    function testToString() {
        $entry = array(
            'username' => isset($username) ? $username : $this->randomString(10),
            'ip' => '127.0.0.1',
            'port' => '8080',
            'corename' => $this->randomString(10),
            'coreversion' => $this->randomString(3),
            'gamename' => $this->randomString(5),
            'gamecrc' => $this->randomString(7),
        );
        $this->registry->insert($entry);

        $entries = $this->registry->selectAll();
        $entry = $entries[0];
        $output = "{$entry['username']}\n{$entry['ip']}\n{$entry['port']}\n{$entry['corename']}\n{$entry['coreversion']}\n{$entry['gamename']}\n{$entry['gamecrc']}\n{$entry['created']}";
        $playlist = new PlaylistFormatter($this->registry);
        $this->assertEquals($output, (string)$playlist);
    }
}