<?php

namespace Espo\Custom\Services;

use \Espo\ORM\Entity;

class Snapclarity extends \Espo\Core\Services\Base
{
    public function resetSnapclarityAssessment($workflowId, Entity $entity, $additionalParameters = null)
    {
        $snapId =  $entity->get('caseid');

        $account = $this->getEntityManager()->getRepository('Account')
            ->findOne();
        $email = $account->get('aPIEmail');
        $password = $account->get('aPIPassword');
		$authUrl = $account->get('authurl');
		$resetAssessmentUrl = $account->get('resetAssessmentUrl');
		
        $response = file_get_contents($authUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-type: application/json",
                'content' => json_encode([
                    'email' => $email,
                    'password' => $password,
                ])
            ]
        ]));
        $data = json_decode($response);
        $token = $data->data->token;

        file_get_contents($resetAssessmentUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-type: application/json\r\n". "x-token: ".$token,
                'content' => json_encode([
                    'uid' => $snapId,
                ])
            ]
        ]));
    }
}