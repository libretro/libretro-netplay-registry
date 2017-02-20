<?php

namespace AppBundle\Entity\ORM;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EntryTest.
 *
 * @covers Entry
 */
class EntryTest extends WebTestCase
{
    /**
     * Tests generic getters and setters from Entry.
     */
    public function testGettersAndSetters()
    {
        $entry = Entry::fromSubmission('steve', 'ParaLLEl', '1.0', 'Mario Kart 64', 'abc123');
        $entry->setHasPassword(true);
        $entry->setGameCRC('123456789');
        $entry->setGameName('Super Smash Bros.');
        $entry->setUsername('David');
        $entry->setCoreName('Shmupen64Plus');
        $entry->setCoreVersion('0.07');
        $entry->setIp('127.0.0.1');
        $entry->setPort('12345');

        $this->assertEquals($entry->getId(), null);
        $this->assertEquals($entry->getGameCRC(), '123456789');
        $this->assertEquals($entry->getGameName(), 'Super Smash Bros.');
        $this->assertEquals($entry->getUsername(), 'David');
        $this->assertEquals($entry->getCoreName(), 'Shmupen64Plus');
        $this->assertEquals($entry->getCoreVersion(), '0.07');
        $this->assertEquals($entry->getIp(), '127.0.0.1');
        $this->assertEquals($entry->getPort(), '12345');
    }
}
