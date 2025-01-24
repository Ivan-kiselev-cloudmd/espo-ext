<?php

namespace Espo\Modules\SnapclaritySync\Core\Listeners;

use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\Contracts\EventHandlerable;

class AutomatedEmailsTrackingRelateToContact implements EventHandlerable
{

    /**
     * @param ContactCreateEvent $event
     * @return mixed|void
     */
    public function handle($event)
    {
        $automatedEmailsTracking = $event->entityManager->getRepository('AutomatedEmailsTracking')->get();
        $event->entityManager->saveEntity($automatedEmailsTracking,[
            'createdById' => 'system'
        ]);

        $event->entityManager
            ->getRepository('Contact')
            ->getRelation($event->contact, 'automatedEmailsTracking')
            ->relate($automatedEmailsTracking);
    }

}