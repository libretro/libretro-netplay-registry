<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class AppControllerTest.
 */
class AppControllerTest extends WebTestCase
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
        $options = array_merge($options, ['command' => $command]);
        return $this->application->run(new ArrayInput($options));
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSubmission()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?username=Test&ip=127.0.0.1&corename=Glilde64&coreversion=0.5&gamename=MarioKart64&gamecrc=abcdefg');

        $em = $this->container->get('doctrine.orm.entity_manager');

        $entries = $em->getRepository('AppBundle:Entry')->findAll();
        $this->assertEquals(1, count($entries));

        $crawler = $client->request('GET', '/?username=Test2&corename=Glilde64&coreversion=0.5&gamename=MarioKart64&gamecrc=abcdefg');
        $entries = $em->getRepository('AppBundle:Entry')->findAll();
        $this->assertEquals(2, count($entries));
    }

    public function testRawApi()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/raw/');
        $this->assertEmpty($client->getResponse()->getContent());

        $crawler = $client->request('GET', '/?username=Test&ip=127.0.0.1&corename=Glilde64&coreversion=0.5&gamename=MarioKart64&gamecrc=abcdefg');
        $crawler = $client->request('GET', '/raw/');

        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
        $this->assertEquals($client->getResponse()->headers->get('Content-Type'), 'text/plain; charset=UTF-8');
    }

}
