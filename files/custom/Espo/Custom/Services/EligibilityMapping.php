<?php

namespace Espo\Custom\Services;

use Espo\Core\ServiceFactory;
use Espo\Core\Templates\Services\Base;
use Espo\Custom\Entities\Eligibility as EligibilityEntity;
use \Espo\Custom\Repositories\Eligibility as EligibilityRepository;
use Espo\Modules\Crm\Entities\Contact as ContactEntity;
use Espo\Modules\Crm\Repositories\Contact as ContactRepository;
use Espo\ORM\EntityCollection;
use Espo\ORM\EntityManager;

class EligibilityMapping extends Base
{
    /** @var EligibilityRepository */
    private $eligibilityRepository;

    /** @var Eligibility */
    private $eligibilityService;

    /** @var ContactRepository */
    private $contactRepository;

//    public function __construct()
//    {
//        parent::__construct();
//
//        /** @var EntityManager $entityManager */
//        $entityManager = $this->getEntityManager();
//        /** @var ServiceFactory $serviceFactory */
//        $serviceFactory = $this->getServiceFactory();
//
//        $this->eligibilityRepository = $entityManager->getRepository('Eligibility');
//        $this->eligibilityService = $serviceFactory->create('Eligibility');
//        $this->contactRepository = $entityManager->getRepository('Contact');
//    }

    /**
     * process mapping for all unmapped eligibilities
     */
    public function mapContacts(): void
    {
        $this->eligibilityService = $this->serviceFactory->create('Eligibility');

        $eligibilities = $this->getUnmappedEligibilities();

        foreach ($eligibilities as $eligibility) {
            $relatedContacts = $this->eligibilityService->findRelatedContacts($eligibility);
            if ($relatedContacts->count() < 1) {
                // TODO add this eligibility to the report
                continue;
            }
            if ($relatedContacts->count() == 1) {
                $this->bind($eligibility, $relatedContacts->current());
            }
            if ($relatedContacts->count() > 1) {
                // TODO add this eligibility and relatedContacts to the report
                continue;
            }
        }
    }

    /**
     * Get list of Eligibility that wasn't mapped
     *
     * @return EntityCollection
     */
    private function getUnmappedEligibilities(): EntityCollection
    {
        $this->eligibilityRepository = $this->entityManager->getRepository('Eligibility');

        $eligibilities = $this->eligibilityRepository
            ->where(['contactId' => null])
            ->find();

        return $eligibilities;
    }

    /**
     * Get list of Eligibility that wasn't mapped
     *
     * @return EntityCollection
     */
    private function getUnmappedContacts(): EntityCollection
    {

    }

    private function bind(EligibilityEntity $eligibility, ContactEntity $contact)
    {
        $this->contactRepository = $this->entityManager->getRepository('Contact');

        $eligibility->set('contactId', $contact->get('id'));

        $this->eligibilityRepository->save($eligibility);
    }

}
