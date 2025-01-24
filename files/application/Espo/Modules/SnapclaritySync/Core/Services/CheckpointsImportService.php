<?php


namespace Espo\Modules\SnapclaritySync\Core\Services;


use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;
use Espo\ORM\Entity;

/**
 * @property array checkpointData
 */
class CheckpointsImportService extends BaseImportService implements EntityDataSetable
{

    /**
     * @var
     */
    private $checkpointData;

    /**
     * @param array $entityData
     * @return $this|mixed
     */
    public function setEntityData(array $entityData)
    {
        $this->checkpointData = $entityData;
        return $this;
    }

    /**
     * @return $this
     */
    public function import()
    {
        /** @var $contact Entity */
        $contact = $this->contactEntity;
        $checkpointData = $this->checkpointData;

        /** @var $entityItem Entity */
        $entityItem = $this->entityManager->getRepository($this->getEntityName())->where([
            'contactId' => $contact->get('id'),
            'deleted' => 0,
            'createdById' => $contact->get('createdById')
        ])->findOne();

        $isCreate = false;

        if (empty($entityItem)) {
            $isCreate = true;
            /** @var $entityItem Entity */
            $entityItem = $this->entityManager->getRepository($this->getEntityName())->get();
        }

        $saveData = [
            'assessmentStarted' => !empty($checkpointData['assessmentStarted']) ? gmdate('Y-m-d H:i:s', strtotime($checkpointData['assessmentStarted'])) : null,
            'crisisScreenVisitDate' => !empty($checkpointData['crisisScreenVisited']) ? gmdate('Y-m-d H:i:s', strtotime($checkpointData['crisisScreenVisited'])) : null,
            'assessmentCompleted' => !empty($checkpointData['assessmentCompleted']) ? gmdate('Y-m-d H:i:s', strtotime($checkpointData['assessmentCompleted'])) : null,
            'name' =>'Checkpoint',
            'contactId' => $contact->get('id')
        ];

        $this->saveEntityUpdatedData($entityItem,$saveData,$isCreate);

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Checkpoints';
    }
}