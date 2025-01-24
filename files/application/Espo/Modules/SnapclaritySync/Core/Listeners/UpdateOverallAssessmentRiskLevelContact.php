<?php

namespace Espo\Modules\SnapclaritySync\Core\Listeners;

use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\ContactUpdateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\Contracts\EventHandlerable;

class UpdateOverallAssessmentRiskLevelContact implements EventHandlerable
{
    /**
     * @param $event
     * @return mixed|void
     */
    public function handle($event)
    {
        if ($event->options['changeAssessmentProgressExist'] === false) {
            return;
        }

        if ($event->contact->get('highRiskFactors') && count($event->contact->get('highRiskFactors')) != 0) {
            $event->contact->set('overallAssessmentRiskLevel', 'High');
        } else if ($event->contact->get('moderateRiskFactors') && count($event->contact->get('moderateRiskFactors')) != 0) {
            $event->contact->set('overallAssessmentRiskLevel', 'Moderate');
        } else if ($event->contact->get('mildRiskFactors') && count($event->contact->get('mildRiskFactors')) != 0) {
            $event->contact->set('overallAssessmentRiskLevel', 'Mild');
        } else {
            $event->contact->set('overallAssessmentRiskLevel', 'No measurable risk');
        }

        $event->entityManager->saveEntity($event->contact);
    }

}