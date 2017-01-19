<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/TestBase.php');

class RegistryTest extends TestBase
{
    function testInsert($username = null) {
        $entry = $this->randomEntry($username);
        $result = $this->registry->insert($entry, false);
        $this->assertTrue($result);
    }

    function testSelectAll() {
        $username = $this->randomString(20);
        $this->testInsert($username);
        $result = $this->registry->selectAll();
        $this->assertEquals($username, $result[0]['username']);
    }
}