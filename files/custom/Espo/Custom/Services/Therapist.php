<?php

namespace Espo\Custom\Services;

use \Espo\ORM\Entity;

class Therapist extends \Espo\Core\Services\Base
{
    public function referMemberToTherapist($workflowId, Entity $entity, $additionalParameters = null)
    {
        $snapclaritySpecialist = $this->getEntityManager()->getRepository('SnapclaritySpecialist')
            ->getRelation($entity, 'snapclaritySpecialists')
            ->findOne();
        $account = $this->getEntityManager()->getRepository('Account')
            ->findOne();
        $organisationId = $account->get('organizationId');
		$referMemberToTherapistUrl = $account->get('referMemberToTherapistUrl');
		$referMemberToTherapistDestinationUrl = $account->get('referMemberToTherapistDestinationUrl');
        $memberCode =  $entity->get('memberCode');

        echo file_get_contents($referMemberToTherapistUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-type: application/json",
                'content' => json_encode([
                    'destination' => $referMemberToTherapistDestinationUrl,
                    'memberCode' => $memberCode,
                    'organizationId' => $organisationId,
                ])
            ]
        ]));
    }
}
