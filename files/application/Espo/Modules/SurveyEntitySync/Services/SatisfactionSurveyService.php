<?php

namespace Espo\Modules\SurveyEntitySync\Services;

use Espo\Modules\SurveyEntitySync\Core\Services\ReAssessmentSurveyMonkeyCollectorIdSave;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\DataProviders\QuestionsAnswersProvider;
use Espo\Modules\SurveyEntitySync\Core\Services\SurveyMonkeyService;
use Espo\ORM\Entity;

class SatisfactionSurveyService extends \Espo\Core\Templates\Services\Base
{
    /**
     * @var SurveyMonkeyService
     */
    protected $surveyMonkeyService;

    /**
     * @var SurveySendToEmail
     */
    protected $surveySendToEmail;

    /**
     * @var ReAssessmentSurveyMonkeyCollectorIdSave
     */
    protected $reAssessmentSurveyMonkeyCollectorIdSave;

    public function __construct(
        SurveyMonkeyService $surveyMonkeyService,
        ReAssessmentSurveyMonkeyCollectorIdSave $reAssessmentSurveyMonkeyCollectorIdSave,
        SurveySendToEmail $surveySendToEmail
    )
    {
        parent::__construct();
        $this->surveyMonkeyService = $surveyMonkeyService;
        $this->reAssessmentSurveyMonkeyCollectorIdSave = $reAssessmentSurveyMonkeyCollectorIdSave;
        $this->surveySendToEmail = $surveySendToEmail;
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
        $this->generateSurvey($entity);
    }

    /**
     * @param Entity $contact
     * @return void
     * @throws \Espo\Core\Exceptions\Error
     */
    public function generateSurvey($contact)
    {
        $pathway = [
            'PAP' => 'SatisfactionSurveyYAP',
            'YAP' => 'SatisfactionSurveyYAP',
            'CAP' => 'SatisfactionSurvey',
            'GAP' => 'SatisfactionSurvey',
        ];

        $casePathway = $contact->get('casePathway');
        if (empty($pathway[$casePathway])) {
            return;
        }

        $satisfaction = $pathway[$casePathway];

        $integration = $this->entityManager->getEntity('Integration', 'SurveyMonkey');
        $developmentMode = !empty($integration->get('isProduction')) ? QuestionsAnswersProvider::ENVIRONMENT_PRODUCTION : QuestionsAnswersProvider::ENVIRONMENT_DEVELOPMENT;

        $contactLanguage = strtolower($contact->get('lang'));
        $surveyData = $this->getMetadata()->get(['surveyEntities',$satisfaction,'surveys',$developmentMode,$contactLanguage]);

        $transaction = $this->entityManager->getTransactionManager();
        $transaction->start();

        $response = $this->generateSurveyMonkeyCollector($contact, $surveyData['id'],$satisfaction);
        $surveyMonkeyCollector = $this->saveSurveyMonkeyCollector($contact,$satisfaction,$contactLanguage,$response);

        $this->surveySendToEmail->send($contact,$surveyMonkeyCollector);

        $transaction->commit();
    }

    /**
     * @param Entity $reassessmentEntity
     * @param Entity $contactEntity
     * @param $language
     * @param $surveyResponse
     * @return Entity|null
     */
    protected function saveSurveyMonkeyCollector(Entity $contactEntity,$satisfactionEntityType,$language, $surveyResponse = [])
    {
        return $this->reAssessmentSurveyMonkeyCollectorIdSave->setSurveyEntityType($satisfactionEntityType)->setContact($contactEntity)->setSurveyDetails([
            'collectorId' => $surveyResponse['id'],
            'surveyUrl' => $surveyResponse['url']
        ])->setLanguage($language)->saveUserReassessmentSurveyMonkeyCollectorId();
    }

    /**
     * @param Entity $contact
     * @param $surveyId
     * @param $satisfaction
     * @return array|false|mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function generateSurveyMonkeyCollector(Entity $contact,$surveyId, $satisfaction)
    {
        return $this->surveyMonkeyService->createSurveyCollector($surveyId,[
            'name' => "{$contact->get('casenum')} - {$satisfaction} - {$contact->get('id')} - {$contact->get('name')}",
            'response_limit' => 1
        ]);
    }

}