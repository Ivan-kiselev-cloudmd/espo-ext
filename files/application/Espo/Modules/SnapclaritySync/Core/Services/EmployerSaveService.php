<?php

namespace Espo\Modules\SnapclaritySync\Core\Services;

use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;

class EmployerSaveService extends BaseImportService implements EntityDataSetable
{

    /**
     * @var
     */
    private $contactEntityDate;

    /**
     * @param array $contactEntityDate
     * @return $this|mixed
     */
    public function setEntityData(array $contactEntityDate = [])
    {
        $this->contactEntityDate = $contactEntityDate;
        return $this;
    }

    /**
     * @return $this
     */
    public function import()
    {
        $kiiOrganizationId = !empty($this->contactEntityDate['kii_organization_id']) ? $this->contactEntityDate['kii_organization_id'] : null;

        if (empty($kiiOrganizationId)) {
            return $this;
        }

        /** @var $entityItem Entity */
        $entityItem = $this->entityManager->getRepository($this->getEntityName())->where([
            'deleted' => 0,
            'kiiOrganizationId' => $kiiOrganizationId
        ])->findOne();

        if (!empty($entityItem)) {
            return $this;
        }

        $entityItem = $this->entityManager->getRepository($this->getEntityName())->get();

        $saveData = [
            'kiiOrganizationId' => $kiiOrganizationId,
            'name' => $kiiOrganizationId,
            'accountId' => $this->getContact()->get('accountId'),
        ];

        $response = $this->saveEntityUpdatedData($entityItem,$saveData,true);

        // save employer id
        $entity = $this->getContact();
        $entity->set([
            'employerId' => $response['entity']->get('id')
        ]);
        $this->entityManager->saveEntity($entity);

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Employer';
    }
}