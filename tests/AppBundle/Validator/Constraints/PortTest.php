<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PortTest.
 *
 * @covers Port
 */
class PortTest extends WebTestCase
{
    public function testMessage() {
        $port = new Port();
        $this->assertTrue(is_string($port->message));
    }
}
