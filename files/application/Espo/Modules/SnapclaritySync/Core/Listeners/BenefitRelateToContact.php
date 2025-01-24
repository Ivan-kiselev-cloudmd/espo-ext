<?php

namespace Espo\Modules\SnapclaritySync\Core\Listeners;

use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\Contracts\EventHandlerable;

class BenefitRelateToContact implements EventHandlerable
{
    /**
     * @param ContactCreateEvent $event
     * @return mixed|void
     */
    public function handle($event)
    {
        $account = $event->entityManager->getRepository('Account')->where([
            'id' => $event->contact->get('accountId')
        ])->findOne();

        if (!$account) {
            return;
        }

        $benefits = $event->entityManager
            ->getRepository('Account')
            ->getRelation($account, 'benefits')
            ->find();

        foreach($benefits as $benefit) {
            $event->entityManager
                ->getRepository('Contact')
                ->getRelation($event->contact, 'benefits')
                ->relate($benefit);
        }
    }

}