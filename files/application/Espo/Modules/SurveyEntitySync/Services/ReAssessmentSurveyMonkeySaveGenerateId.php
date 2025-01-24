<?php

namespace Espo\Modules\SurveyEntitySync\Services;

use Espo\Modules\SurveyEntitySync\Core\Services\ReAssessmentSurveyMonkeyCollectorIdSave;
use Espo\Modules\SurveyEntitySync\Core\Services\SurveyMonkeyService;
use Espo\ORM\Entity;

class ReAssessmentSurveyMonkeySaveGenerateId extends \Espo\Core\Templates\Services\Base
{
    /**
     * @var SurveyMonkeyService
     */
    protected $surveyMonkeyService;

    /**
     * @var ReAssessmentSurveyMonkeyCollectorIdSave
     */
    protected $reAssessmentSurveyMonkeyCollectorIdSave;

    /**
     * @param SurveyMonkeyService $surveyMonkeyService
     * @param ReAssessmentSurveyMonkeyCollectorIdSave $reAssessmentSurveyMonkeyCollectorIdSave
     */
    public function __construct(
        SurveyMonkeyService $surveyMonkeyService,
        ReAssessmentSurveyMonkeyCollectorIdSave $reAssessmentSurveyMonkeyCollectorIdSave
    )
    {
        parent::__construct();
        $this->surveyMonkeyService = $surveyMonkeyService;
        $this->reAssessmentSurveyMonkeyCollectorIdSave = $reAssessmentSurveyMonkeyCollectorIdSave;
    }

    /**
     * @param $workflowId
     * @param Entity $entity
     * @param $additionalParameters
     * @return void
     * @throws \Espo\Core\Exceptions\Error
     */
    public function handle($workflowId, Entity $entity, $additionalParameters = null)
    {
        $riskCategory = $this->getEntityManager()->getRepository('RiskCategory')->where([
            'entityType' => $entity->getEntityType()
        ])->findOne();

        if (empty($riskCategory)) {
            $GLOBALS['log']->info('RiskCategory not found',[
                'id' => $entity->get('id'),
                'entityType' => $entity->getEntityType()
            ]);
            return;
        }

        $contact = $this->getEntityManager()->getRepository('Contact')->where([
            'id' => $entity->get('contactId')
        ])->findOne();

        if (empty($contact)) {
            $GLOBALS['log']->info('RiskFactor entity contactNotFound',[
                'entityType' => $entity->getEntityType(),
                'id' => $entity->get('id'),
                'contactId' => $entity->get('contactId')
            ]);
            return;
        }

        $contactLanguage = strtolower($contact->get('lang'));

        if ($riskCategory->get('surveyEn') && $contactLanguage == 'en') {
            $response = $this->generateSurveyMonkeyCollector($contact, $riskCategory->get('surveyEn'));
            $this->saveSurveyMonkeyCollector($entity, $contact,'en',$response);
        }
        if ($riskCategory->get('surveyFr') && $contactLanguage == 'fr') {
            $response = $this->generateSurveyMonkeyCollector($contact, $riskCategory->get('surveyFr'));
            $this->saveSurveyMonkeyCollector($entity, $contact,'fr',$response);
        }
    }

    /**
     * @param Entity $reassessmentEntity
     * @param Entity $contactEntity
     * @param $language
     * @param array $surveyResponse
     * @return void
     */
    protected function saveSurveyMonkeyCollector(Entity $reassessmentEntity, Entity $contactEntity,$language, $surveyResponse = [])
    {
        $this->reAssessmentSurveyMonkeyCollectorIdSave->setSurveyEntity($reassessmentEntity)->setContact($contactEntity)->setSurveyDetails([
            'collectorId' => $surveyResponse['id'],
            'surveyUrl' => $surveyResponse['url']
        ])->setLanguage($language)->saveUserReassessmentSurveyMonkeyCollectorId();
    }

    /**
     * @param Entity $riskCategory
     * @param Entity $contact
     * @return array|false|mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function generateSurveyMonkeyCollector(Entity $contact,$surveyId)
    {
        return $this->surveyMonkeyService->createSurveyCollector($surveyId,[
            'name' => "{$contact->get('casenum')} - {$contact->get('id')} - {$contact->get('name')}",
            'response_limit' => 1
        ]);
    }
}