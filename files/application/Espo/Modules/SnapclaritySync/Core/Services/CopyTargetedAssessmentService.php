<?php

namespace Espo\Modules\SnapclaritySync\Core\Services;

use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;
use Espo\ORM\Entity;

class CopyTargetedAssessmentService extends BaseImportService
{
    const TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_ALCOHOL_USE_DISORDER = 'ReassessmentAlcoholUseDisorder';
    const TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_DEPRESSION = 'ReassessmentDepression';
    const TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_GAD = 'ReassessmentGAD';
    const TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_SLEEP_DISORDER = 'ReassessmentSleepDisorder';

    protected $entityFields = [
        self::TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_ALCOHOL_USE_DISORDER => [
            'copied_fields_from_targeted_assessment' => [
                'q1' => 'aud1',
                'q2' => 'aud2',
                'q3' => 'aud3',
                'q4' => 'aud4',
                'q5' => 'aud5',
                'q6' => 'aud6',
                'q7' => 'aud7',
                'q8' => 'aud8',
                'q9' => 'aud9',
                'q10' => 'aud10',
                'date' => 'dateAssessmentCompleted'
            ]
        ],
        self::TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_DEPRESSION => [
            'copied_fields_from_targeted_assessment' => [
                'q1' => 'ds1',
                'q2' => 'ds2',
                'q3' => 'ds3',
                'q4' => 'ds4',
                'q5' => 'ds5',
                'q6' => 'ds6',
                'q7' => 'ds7',
                'q8' => 'ds8',
                'q9' => 'ds9',
                'date' => 'dateAssessmentCompleted'
            ]
        ],
        self::TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_GAD => [
            'copied_fields_from_targeted_assessment' => [
                'q1' => 'gad1',
                'q2' => 'gad2',
                'q3' => 'gad3',
                'q4' => 'gad4',
                'q5' => 'gad5',
                'q6' => 'gad6',
                'q7' => 'gad7',
                'date' => 'dateAssessmentCompleted'
            ]
        ],
        self::TARGETED_ASSESSMENT_ENTITY_REASSESSMENT_SLEEP_DISORDER => [
            'copied_fields_from_targeted_assessment' => [
                'q1' => 'sl1',
                'q2' => 'sl2',
                'q3' => 'sl3',
                'q4' => 'sl4',
                'q5' => 'sl5',
                'q6' => 'sl6',
                'q7' => 'sl7',
                'date' => 'dateAssessmentCompleted'
            ]
        ]
    ];

    /**
     * @var
     */
    protected $entityName = null;

    /**
     * @return $this
     */
    public function import()
    {
        /** @var $targetedAssessmentEntityItem Entity */
        $targetedAssessmentEntityItem = $this->getTargetedAssessment();

        if (empty($targetedAssessmentEntityItem)) {
            return $this;
        }

        foreach($this->entityFields as $entityType => $entityReplaceData) {
            $entityItem = $this->entityManager->getRepository($entityType)->get();

            $this->entityName = $entityType;
            $saveData = [
                'contactId' => $this->contactEntity->get('id'),
                'type' => 'Current Baseline',
                'date' => $this->contactEntity->get('date')
            ];
            foreach($entityReplaceData['copied_fields_from_targeted_assessment'] as $entityFieldKey => $targetedAssessmentFieldKey) {
                $saveData = array_merge($saveData, [
                    $entityFieldKey => $targetedAssessmentEntityItem->get($targetedAssessmentFieldKey)
                ]);
            }

            $this->saveEntityUpdatedData($entityItem,$saveData,true);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTargetedAssessment()
    {
        return $this->entityManager->getRepository('TargetedAssessment')->where([
            'contactId' => $this->contactEntity->get('id'),
            'source' => 'Initial',
            'deleted' => 0
        ])->findOne();
    }

    /**
     * @return mixed|string
     */
    protected function getEntityName()
    {
        return $this->entityName;
    }
}