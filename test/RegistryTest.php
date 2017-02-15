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
        $username = $this->randomString(20);
        $this->testInsert($username);
        $result = $this->registry->selectAll();
        $this->assertEquals($username, $result[0]['username']);
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
}
