<?php

namespace Espo\Modules\SnapclaritySync\Core\Listeners;

use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\Contracts\EventHandlerable;

class MedicalIntakeRelateToContact implements EventHandlerable
{

    /**
     * @param ContactCreateEvent $event
     * @return mixed|void
     */
    public function handle($event)
    {
        $medicalIntake = $event->entityManager->getRepository('MedicalIntake')->get();
        $event->entityManager->saveEntity($medicalIntake,[
            'createdById' => 'system'
        ]);

        $event->entityManager
            ->getRepository('Contact')
            ->getRelation($event->contact, 'medicalIntake')
            ->relate($medicalIntake);
    }

}