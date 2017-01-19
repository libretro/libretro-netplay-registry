<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/TestBase.php');

class RegistryTest extends TestBase
{
    function testInsert($username = null) {
        $entry = array(
            'username' => isset($username) ? $username : $this->randomString(10),
            'corename' => $this->randomString(10),
            'coreversion' => $this->randomString(3),
            'gamename' => $this->randomString(5),
            'gamecrc' => $this->randomString(7),
        );
        $result = $this->registry->insert($entry);
        $this->assertTrue($result);
    }

    function testSelectAll() {
        $username = $this->randomString(20);
        $this->testInsert($username);
        $result = $this->registry->selectAll();
        $this->assertEquals($username, $result[0]['username']);
    }
}