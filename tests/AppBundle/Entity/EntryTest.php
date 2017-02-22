<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EntryTest.
 *
 * @covers AppBundle\Entity\Entry
 */
class EntryTest extends WebTestCase
{
    /**
     * Tests generic getters and setters from Entry.
     */
    public function testGettersAndSetters()
    {
        $dateTime = new \DateTime();

        $entry = Entry::fromSubmission('test', 'test', 'test', 'test', 'test');
        $entry->setHasPassword(true);
        $entry->setGameCRC('123456789');
        $entry->setGameName('Super Smash Bros.');
        $entry->setUsername('David');
        $entry->setCoreName('Shmupen64Plus');
        $entry->setCoreVersion('0.07');
        $entry->setIp('127.0.0.1');
        $entry->setPort('12345');
        $entry->setCreatedAt($dateTime);

        $this->assertNull($entry->getId());
        $this->assertEquals($entry->getGameCRC(), '123456789');
        $this->assertEquals($entry->getGameName(), 'Super Smash Bros.');
        $this->assertEquals($entry->getUsername(), 'David');
        $this->assertEquals($entry->getCoreName(), 'Shmupen64Plus');
        $this->assertEquals($entry->getCoreVersion(), '0.07');
        $this->assertEquals($entry->getIp(), '127.0.0.1');
        $this->assertEquals($entry->getPort(), '12345');
        $this->assertEquals($entry->getCreatedAt(), $dateTime);
        $this->assertTrue($entry->hasPassword());
    }

    public function testSerialization()
    {
        $entry = Entry::fromSubmission('test', 'test', 'test', 'test', 'test');
        $entry->setIp('test');
        $serialized = $entry->serialize();
        $this->assertTrue(is_string($serialized));

        $newEntry = Entry::fromSubmission('', '', '' ,'' ,'');
        $newEntry->unserialize($serialized);
        $this->assertNull($entry->getId());
        $this->assertEquals($entry->getUsername(), 'test');
        $this->assertEquals($entry->getIp(), 'test');
        $this->assertEquals($entry->getGameName(), 'test');
        $this->assertEquals($entry->getGameCRC(), 'test');
        $this->assertEquals($entry->getCoreVersion(), 'test');
        $this->assertEquals($entry->getCoreName(), 'test');
        $this->assertFalse($entry->hasPassword());
    }

    public function testJsonSerialize()
    {
        $entry = Entry::fromSubmission('test', 'test', 'test', 'test', 'test');
        $jsonString = json_encode($entry);
        $this->assertTrue(is_string($jsonString));

        json_decode($jsonString);
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }
}
