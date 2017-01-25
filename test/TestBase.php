<?php

namespace RobLoach\LibretroNetplayRegistry\Test;

use PHPUnit\Framework\TestCase;
use RobLoach\LibretroNetplayRegistry\Registry;

require_once __DIR__ . '/../src/autoload.php';

class TestBase extends TestCase
{
    protected $registry;
    protected $name;

    protected function setUp()
    {
        // Create a new registry for each test.
        $this->name = '.test';
        $this->registry = new Registry($this->name);

        // Make sure the registry is clear.
        $this->registry->clearOld(-9999);
    }

    protected function tearDown()
    {
        unlink("{$this->name}.sqlite");
    }

    protected function randomEntry($username = null)
    {
        $entry = array(
            'username' => isset($username) ? $username : $this->randomString(10),
            'ip' => '127.0.0.1',
            'port' => '8080',
            'corename' => $this->randomString(10),
            'coreversion' => $this->randomString(3),
            'gamename' => $this->randomString(5),
            'gamecrc' => $this->randomString(7),
        );
        return $entry;
    }

    protected function randomString($length = 5)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }
}
