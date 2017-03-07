<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class EntryManagementListenerTest.
 *
 * @covers EntryManagementListener
 */
class EntryManagementListenerTest extends WebTestCase
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

    public function testPostPersist() {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $comboLimit = $this->container->getParameterBag()->get('entry_limit_per_ip_username_combo');
        $ipLimit = $this->container->getParameterBag()->get('entry_limit_per_ip');

        $newestEntry = Entry::fromSubmission('steve', 'test', 'test', 'test', 'test');
        $newestEntry->setIp('127.0.0.1');
        $em->persist($newestEntry);

        $otherNewerEntry = Entry::fromSubmission('bob', 'test', 'test', 'test', 'test');
        $otherNewerEntry->setIp('127.0.0.1');
        $otherNewerEntry->setCreatedAt(new \DateTime('+5 minutes'));
        $em->persist($otherNewerEntry);

        $em->flush();

        $oldDate = new \DateTime('-5 minutes');
        for ($i = 0; $i < $ipLimit + 5; ++$i) {
            $entry = Entry::fromSubmission((string) $i, 'test', 'test', 'test', 'test');
            $entry->setIp('127.0.0.1');
            $entry->setCreatedAt($oldDate);
            $em->persist($entry);
        }
        $em->flush();

        $entriesWithSameIp = $em->getRepository('AppBundle:Entry')->findBy(['ip' => '127.0.0.1'], ['createdAt' => 'DESC']);
        $usernames = [];
        foreach ($entriesWithSameIp as $entryWithSameIp) {
            $usernames[] = $entryWithSameIp->getUsername();
        }

        $this->assertEquals($ipLimit, count($entriesWithSameIp));
        $this->assertContains('steve', $usernames);
        $this->assertContains('bob', $usernames);

        $johnsLatestEntry = Entry::fromSubmission('john', 'test', 'test', 'HINT', 'test');
        $johnsLatestEntry->setIp('192.168.1.16');
        $johnsLatestEntry->setCreatedAt(new \DateTime('+5 minutes'));
        $em->persist($johnsLatestEntry);
        $em->flush($johnsLatestEntry);

        for ($i = 0; $i < $comboLimit + 5; ++$i) {
            $entry = Entry::fromSubmission('john', 'test', 'test', 'test', 'test');
            $entry->setIp('192.168.1.16');
            $em->persist($entry);
        }
        $em->flush();

        $johnsEntries = $em->getRepository('AppBundle:Entry')->findBy(['ip' => '192.168.1.16', 'username' => 'john'], ['createdAt' => 'DESC']);
        $games = [];
        foreach ($johnsEntries as $johnsEntry) {
            $games[] = $johnsEntry->getGameName();
        }

        $this->assertEquals($comboLimit, count($johnsEntries));
        $this->assertContains('HINT', $games);
    }
}
