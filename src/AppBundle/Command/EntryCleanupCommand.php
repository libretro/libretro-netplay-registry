<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class EntryCleanupCommand.
 */
class EntryCleanupCommand extends ContainerAwareCommand
{
    /**
     * Configures the command properties.
     */
    protected function configure()
    {
        $this
            ->setName('app:entry:cleanup')
            ->setDescription('Removes old entries from the database.')
            ->setHelp(
                'This command will get every entry in the database, '.
                'and compare the createdAt attribute with the current timestamp. '.
                'Entries older than 2 Minutes will be deleted.'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $io = new SymfonyStyle($input, $output);
        $io->title('Cleaning up old entries');
        $io->section('Statistic');

        $entries = $em->getRepository('AppBundle:Entry')->findAll();
        $io->writeln('A total amount of ' . count($entries) . ' entries where found.');

        $counter = 0;
        $filter = new \DateTime('-2 minutes');
        foreach ($entries as $entry) {
            if ($entry->getCreatedAt() < $filter) {
                ++$counter;
                $em->remove($entry);
            }
        }
        $io->writeln($counter. ' entries are being deleted.');
        $em->flush();
    }
}
