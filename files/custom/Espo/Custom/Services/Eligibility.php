<?php

namespace Espo\Custom\Services;

use Espo\Core\Templates\Services\Base;
use Espo\Custom\Entities\Eligibility as EligibilityEntity;
use Espo\ORM\EntityCollection;

class Eligibility extends Base
{

    /**
     * Search cases with the same properties (first and last name)
     *
     * @param EligibilityEntity $eligibility
     *
     * @return EntityCollection
     */
    public function findRelatedContacts(EligibilityEntity $eligibility): EntityCollection
    {
        /** @var Contact $contactService */
        $contactService = $this->serviceFactory->create('Contact');
        $firstName = $eligibility->get('firstName');
        $lastName = $eligibility->get('lastName');

        return $contactService->searchByFirstLastName($firstName, $lastName);

    }
}
