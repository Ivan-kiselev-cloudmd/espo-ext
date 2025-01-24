<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization;

use Espo\Custom\Entities\ReassessmentADHD;
use Espo\Custom\Entities\ReassessmentAlcoholUseDisorder;
use Espo\Custom\Entities\ReassessmentAngerManagement;
use Espo\Custom\Entities\ReassessmentBipolar;
use Espo\Custom\Entities\ReassessmentDepression;
use Espo\Custom\Entities\ReassessmentEatingDisorder;
use Espo\Custom\Entities\ReassessmentGAD;
use Espo\Custom\Entities\ReassessmentOCD;
use Espo\Custom\Entities\ReassessmentPanicDisorder;
use Espo\Custom\Entities\ReassessmentPTSD;
use Espo\Custom\Entities\ReassessmentSAD;
use Espo\Custom\Entities\ReassessmentSleepDisorder;
use Espo\Custom\Entities\ReassessmentSUD;
use Espo\Custom\Entities\SatisfactionSurvey;
use Espo\Custom\Entities\SatisfactionSurveyYAP;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\DataProviders\QuestionsAnswersProvider;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\SaveProcessors\ReassessmentSaveProcessor;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\SaveProcessors\SatisfactionSaveProcessor;
use Espo\Modules\SurveyEntitySync\Core\Services\SurveyMonkeyService;
use Espo\ORM\EntityManager;
use Espo\Core\Container;

class BaseReassessmentSynchronization
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SurveyMonkeyService
     */
    protected $surveyMonkeyService;

    /**
     * @var QuestionsAnswersProvider
     */
    protected $questionsAnswersProvider;

    /**
     * @var QuestionData
     */
    protected $questionData;

    /**
     * @var SurveyQuestionsFieldsParser
     */
    protected $surveyQuestionsFieldsParser;

    /**
     * @var SurveyQuestionAnswerParser
     */
    protected $surveyQuestionAnswerParser;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ReassessmentSaveProcessor
     */
    protected $reassessmentSaveProcessor;

    /**
     * @var ReassessmentSaveProcessor
     */
    protected $satisfactionSaveProcessor;

    /**
     * @param EntityManager $entityManager
     * @param SurveyMonkeyService $surveyMonkeyService
     * @param QuestionsAnswersProvider $questionsAnswersProvider
     * @param QuestionData $questionData
     * @param SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser
     * @param SurveyQuestionAnswerParser $surveyQuestionAnswerParser
     * @param Container $container
     * @param ReassessmentSaveProcessor $reassessmentSaveProcessor
     * @param SatisfactionSaveProcessor $satisfactionSaveProcessor
     */
    public function __construct(
        EntityManager $entityManager,
        SurveyMonkeyService $surveyMonkeyService,
        QuestionsAnswersProvider $questionsAnswersProvider,
        QuestionData $questionData,
        SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser,
        SurveyQuestionAnswerParser $surveyQuestionAnswerParser,
        Container $container,
        ReassessmentSaveProcessor $reassessmentSaveProcessor,
        SatisfactionSaveProcessor $satisfactionSaveProcessor
    )
    {
        $this->entityManager = $entityManager;
        $this->surveyMonkeyService = $surveyMonkeyService;
        $this->questionsAnswersProvider = $questionsAnswersProvider;
        $this->questionData = $questionData;
        $this->surveyQuestionsFieldsParser = $surveyQuestionsFieldsParser;
        $this->surveyQuestionAnswerParser = $surveyQuestionAnswerParser;
        $this->container = $container;
        $this->reassessmentSaveProcessor = $reassessmentSaveProcessor;
        $this->satisfactionSaveProcessor = $satisfactionSaveProcessor;
    }

    /**
     * @return void
     * @throws \Espo\Core\Exceptions\Error
     */
    public function sync()
    {
        $surveys = $this->getSurveys();
        $syncLastMinutes =  !empty($this->getIntegrationConfig()->get('syncLastMinutes')) ? $this->getIntegrationConfig()->get('syncLastMinutes') : 100;
        $lastSinceDate = (new \DateTime("-${syncLastMinutes} minutes"))->format('Y-m-d\TH:i:s.v');
        $saveProcessors = $this->saveSaveProcessors();

        foreach($surveys as $reassessmentNamespace => $surveyData) {
            foreach($surveyData['survey'] as $locale => $surveyItem) {
                $page = 1;
                $perPage = 100;
                $surveyId = $surveyItem['id'];

                while(true) {
                    $response = $this->surveyMonkeyService->getSurveyResponses($surveyId,[
                        'page' => $page,
                        'per_page' => $perPage,
                        'sort_order' => 'desc',
                        'status' => 'completed',
                        'start_created_at' => $lastSinceDate,
                    ]);

                    foreach($response['data'] as $response) {
                        $saveProcessors[$reassessmentNamespace]()->save($response, $surveyData, $locale);
                    }

                    if(empty($response['links']['next'])) {
                        $GLOBALS['log']->error('Break End surveyId: ' . $surveyId);
                        break;
                    }

                    $page++;
                }
            }
        }
    }

    /**
     * @return array[]|\array[][]
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getSurveys()
    {
        return $this->questionsAnswersProvider->getQuestionsData();
    }

    /**
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getIntegrationConfig()
    {
        return $this->container->get('entityManager')->getEntity('Integration', 'SurveyMonkey');
    }

    /**
     * @return \Closure[]
     */
    protected function saveSaveProcessors()
    {
        return [
            ReassessmentGAD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentADHD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentAlcoholUseDisorder::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentAngerManagement::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentBipolar::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentDepression::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentEatingDisorder::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentOCD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentPanicDisorder::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentPTSD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentSAD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentSleepDisorder::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            ReassessmentSUD::class => function () {
                return $this->reassessmentSaveProcessor;
            },
            SatisfactionSurvey::class => function () {
                return $this->satisfactionSaveProcessor;
            },
            SatisfactionSurveyYAP::class => function () {
                return $this->satisfactionSaveProcessor;
            }
        ];
    }

}