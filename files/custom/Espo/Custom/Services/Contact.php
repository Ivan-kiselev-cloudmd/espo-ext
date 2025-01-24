<?php

namespace Espo\Custom\Services;

use Espo\ORM\EntityCollection;

class Contact extends \Espo\Modules\Crm\Services\Contact
{

    public function searchByFirstLastName(string $firstName, string $lastName): EntityCollection
    {
        $eligibilityRepository = $this->getEntityManager()->getRepository('Contact');

        $result = $eligibilityRepository
            ->where([
                'firstName' => $firstName,
                'lastName' => $lastName
            ])
            ->find();

        return $result;
    }
}
