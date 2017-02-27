<?php

namespace AppBundle\Command;

use AppBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class EntryCleanupCommandTest.
 *
 * @covers EntryCleanupCommand
 */
class EntryCleanupCommandTest extends WebTestCase
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

    public function testExecute()
    {
        $command       = $this->application->find('app:entry:cleanup');
        $commandTester = new CommandTester($command);
        $em = $this->container->get('doctrine.orm.entity_manager');

        $commandTester->execute(
            [
                'command' => $command->getName(),
            ]
        );
        $output = $commandTester->getDisplay();
        $this->assertContains('A total amount of 0 entries where found.', $output);
        $this->assertContains('0 entries are being deleted.', $output);

        $entry = Entry::fromSubmission('test', 'test', 'test', 'test', 'test');
        $entry->setIp('192.168.1.14');
        $em->persist($entry);
        $em->flush();
        $commandTester->execute(
            [
                'command' => $command->getName()
            ]
        );
        $output = $commandTester->getDisplay();
        $this->assertContains('A total amount of 1 entries where found.', $output);
        $this->assertContains('0 entries are being deleted.', $output);

        $entry = Entry::fromSubmission('test', 'test', 'test', 'test', 'test');
        $entry->setCreatedAt(new \DateTime('-3 minutes'));
        $entry->setIp('192.168.1.15');
        $em->persist($entry);
        $em->flush();
        $commandTester->execute(
            [
                'command' => $command->getName()
            ]
        );
        $output = $commandTester->getDisplay();
        $this->assertContains('A total amount of 2 entries where found.', $output);
        $this->assertContains('1 entries are being deleted.', $output);
    }
}
