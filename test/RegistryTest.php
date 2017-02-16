<?php

namespace RobLoach\LibretroNetplayRegistry\Test;

use PHPUnit\Framework\TestCase;

/**
 * Class RegistryTest.
 */
class RegistryTest extends TestBase
{
    /**
     * @param null $username
     */
    public function testInsert($username = null)
    {
        $entry = $this->randomEntry($username);
        $result = $this->registry->insert($entry, false);
        $this->assertTrue($result);
    }

    public function testSelectAll()
    {
        // Add an entry to the registry.
        $username = $this->randomString(20);
        $entry = $this->randomEntry($username);
        $this->registry->insert($entry, false);

        // Test that select all is functional.
        $result = $this->registry->selectAll();
        $this->assertEquals($username, $result[0]['username']);
        $this->assertEquals(true, $result[0]['haspassword']);
    }

    public function testInsertDuplicate()
    {
        $entry = $this->randomEntry();
        $this->registry->insert($entry, false);
        $result = $this->registry->selectAll();
        $this->assertEquals(1, sizeof($result));

        $this->registry->insert($entry, false);
        $result = $this->registry->selectAll();
        $this->assertEquals(1, sizeof($result));

        $entry['coreversion'] = '1.2';
        $this->registry->insert($entry, false);
        $result = $this->registry->selectAll();
        $this->assertEquals(1, sizeof($result));
        $this->assertEquals('1.2', $result[0]['coreversion']);
    }

    public function testIsConnectable()
    {
        $this->assertTrue($this->registry->isConnectable('google.com', 80));
        $this->assertFalse($this->registry->isConnectable('127.0.0.1', 99999));
    }
}
