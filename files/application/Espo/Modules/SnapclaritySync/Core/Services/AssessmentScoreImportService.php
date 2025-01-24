<?php


namespace Espo\Modules\SnapclaritySync\Core\Services;


use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;
use Espo\ORM\Entity;

/**
 * @property array assessmentEntityData
 */
class AssessmentScoreImportService extends BaseImportService implements EntityDataSetable
{

    /**
     * @var
     */
    private $assessmentEntityData;

    /**
     * @param array $entityData
     * @return $this|mixed
     */
    public function setEntityData(array $entityData)
    {
        $this->assessmentEntityData = $entityData;
        return $this;
    }

    /**
     * @return $this
     */
    public function import()
    {
        /** @var $contact Entity */
        $contact = $this->contactEntity;
        $scoreData = $this->assessmentEntityData['data'];

        $entityItem = $this->entityManager->getRepository($this->getEntityName())->where([
            'deleted' => 0,
            'contactId' => $contact->get('id'),
            'name' => $scoreData['name']
        ])->findOne();
        $isCreate = false;

        if (empty($entityItem)) {
            $isCreate = true;
            /** @var $entityItem Entity */
            $entityItem = $this->entityManager->getRepository($this->getEntityName())->get();
        }

        if ($scoreData['score'] === 0) {
            return $this;
        }

        $saveData = [
            'name' => !empty($scoreData['name']) ? $scoreData['name'] : null,
            'level' => !empty($scoreData['level']) ? $scoreData['level'] : null,
            'score' => array_key_exists('score',$scoreData) ? $scoreData['score'] : null,
            'diagnosisname' => !empty($scoreData['name']) ? $scoreData['name'] : null,
            'contactId' => $contact->get('id'),
            'createdById' => $contact->get('createdById')
        ];

        $this->saveEntityUpdatedData($entityItem,$saveData,$isCreate);

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'AssessmentScore';
    }
}