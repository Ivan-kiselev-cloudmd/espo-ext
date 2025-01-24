<?php

namespace Espo\Modules\SurveyEntitySync\Core\Services;

use Espo\Core\ORM\EntityManager;
use Espo\ORM\Entity;

class ReAssessmentSurveyMonkeyCollectorIdSave
{
    /**
     * @var
     */
    protected $entityManager;

    /**
     * @var Entity
     */
    protected $surveyableEntity;

    /**
     * @var Entity
     */
    protected $contactEntity;

    /**
     * @var
     */
    protected $surveyEntityType;
    /**
     * @var
     */
    protected $surveyEntityId;

    /**
     * @var
     */
    protected $surveyResponseDetails = [];

    /**
     * @var
     */
    protected $language = 'en';

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Entity|null
     */
    public function saveUserReassessmentSurveyMonkeyCollectorId()
    {
        $surveyMonkeyCollector = $this->entityManager->getRepository('SurveyMonkeyCollector')->get();
        $surveyMonkeyCollector->set([
            'name' => "{$this->surveyEntityType} {$this->contactEntity->get('casenum')} {$this->contactEntity->get('id')}",
            'entityType' => $this->surveyEntityType,
            'entityId' => $this->surveyEntityId,
            'collectorId' => $this->surveyResponseDetails['collectorId'],
            'collectorUrl' => $this->surveyResponseDetails['surveyUrl'],
            'contactId' => $this->contactEntity->get('id'),
            'language' => $this->language,
        ]);

        $this->entityManager->saveEntity($surveyMonkeyCollector);

        return $surveyMonkeyCollector;
    }

    /**
     * @param $surveyResponseDetails
     * @return $this
     */
    public function setSurveyDetails($surveyResponseDetails = [])
    {
        $this->surveyResponseDetails = $surveyResponseDetails;
        return $this;
    }

    /**
     * @param Entity $entity
     * @return $this
     */
    public function setSurveyEntity(Entity $entity)
    {
        $this->surveyEntityType = $entity->getEntityType();
        $this->surveyEntityId = $entity->get('id');

        return $this;
    }

    /**
     * @param $surveyEntityType
     * @return $this
     */
    public function setSurveyEntityType($surveyEntityType)
    {
        $this->surveyEntityType = $surveyEntityType;
        return $this;
    }

    /**
     * @param $surveyEntityId
     * @return $this
     */
    public function setSurveyEntityId($surveyEntityId)
    {
        $this->surveyEntityId = $surveyEntityId;
        return $this;
    }

    /**
     * @param Entity $entity
     * @return $this
     */
    public function setContact(Entity $entity)
    {
        $this->contactEntity = $entity;
        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language = 'en')
    {
        $this->language = $language;
        return $this;
    }
}