<?php

namespace Tests\AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class EntryControllerTest.
 *
 * @covers AppBundle\Controller\API\EntryController
 */
class EntryControllerTest extends WebTestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * Prepares environment for tests.
     */
    public function setup()
    {
        self::bootKernel();
        $this->application = new \Symfony\Bundle\FrameworkBundle\Console\Application(self::$kernel);
        $this->application->setAutoExit(false);
        $this->runConsole('doctrine:schema:drop', ['--force' => true]);
        $this->runConsole('doctrine:schema:create');
        $this->container = self::$kernel->getContainer();
    }

    /**
     * @param       $command
     * @param array $options
     *
     * @return mixed
     */
    protected function runConsole($command, array $options = [])
    {
        $options['-e'] = 'test';
        $options['-q'] = null;
        $options       = array_merge($options, ['command' => $command]);

        return $this->application->run(new ArrayInput($options));
    }

    public function testListAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/entry/data.raw');
        $this->assertEmpty($client->getResponse()->getContent());
        $this->assertEquals($client->getResponse()->headers->get('Content-Type'), 'text/plain; charset=UTF-8');

        $client->request('GET', '/api/entry/data');
        $jsonContent = json_decode($client->getResponse()->getContent());
        $this->assertEmpty($jsonContent);
        $this->assertTrue(is_array($jsonContent));
        $client->request('GET', '/api/entry/data.json');
        $jsonContent2 = json_decode($client->getResponse()->getContent());
        $this->assertEquals($jsonContent, $jsonContent2);

        $client->request('GET', '/api/entry/data.xml');
        $xmlContent = simplexml_load_string($client->getResponse()->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
        $xmlEntries = $xmlContent->xpath('//entry');
        $this->assertEmpty($xmlEntries);

        $client->request('GET', '/?username=Test&ip=127.0.0.1&corename=Glilde64&coreversion=0.5&gamename=MarioKart64&gamecrc=abcdefg');
        $client->request('GET', '/?username=Test&ip=127.0.0.1&corename=Glilde64&coreversion=0.5&gamename=MarioKart64&gamecrc=abcdefg');

        $client->request('GET', '/api/entry/data.raw');
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertEquals($client->getResponse()->headers->get('Content-Type'), 'text/plain; charset=UTF-8');

        $client->request('GET', '/api/entry/data.json');
        $jsonEntries = json_decode($client->getResponse()->getContent());
        $this->assertEquals(2, count($jsonEntries));

        $client->request('GET', '/api/entry/data.xml');
        $xmlContent = simplexml_load_string($client->getResponse()->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
        $xmlEntries  = $xmlContent->xpath('//entry');
        $this->assertEquals(2, count($xmlEntries));
    }
}
