<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Entry;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class EntryManagementListener.
 */
class EntryManagementListener
{
    /**
     * @var int
     */
    private $entriesPerUsernameIp;

    /**
     * @var int
     */
    private $entriesPerIp;

    /**
     * EntryManagementListener constructor.
     *
     * @param $entriesPerUsernameIp
     * @param $entriesPerIp
     */
    public function __construct($entriesPerUsernameIp, $entriesPerIp)
    {
        $this->entriesPerUsernameIp = $entriesPerUsernameIp;
        $this->entriesPerIp         = $entriesPerIp;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Entry) {
            return;
        }
        $em = $args->getEntityManager();

        // Get entries from database. Newest first.
        $similarEntries = $em->getRepository('AppBundle:Entry')->findBy(['ip' => $entity->getIp()], ['createdAt' => 'ASC']);

        $counter     = 0;
        $pairCounter = 0;
        foreach ($similarEntries as $similarEntry) {
            ++$counter;
            if ($counter > $this->entriesPerIp) { // Remove if more entries in database than allowed.
                $em->remove($similarEntry);
                continue;
            }
            if ($entity->getUsername() === $similarEntry->getUsername()) {
                ++$pairCounter;
                if ($pairCounter > $this->entriesPerUsernameIp) {
                    $em->remove($similarEntry);
                }
            }
        }
        $em->flush();
    }
}
