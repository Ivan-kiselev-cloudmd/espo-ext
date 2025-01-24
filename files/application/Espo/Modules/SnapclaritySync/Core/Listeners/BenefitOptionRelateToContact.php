<?php

namespace Espo\Modules\SnapclaritySync\Core\Listeners;

use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\Contracts\EventHandlerable;

class BenefitOptionRelateToContact implements EventHandlerable
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

        $benefitOption = $event->entityManager
            ->getRepository('Account')
            ->getRelation($account, 'benefitOptions')
            ->order('createdAt', 'DESC')
            ->where([
                '@benefit_option.default' => true,
            ])
            ->findOne();

        if ($benefitOption) {
            $event->entityManager
                ->getRepository('Contact')
                ->getRelation($event->contact, 'benefitOption')
                ->relate($benefitOption);
        }
    }

}