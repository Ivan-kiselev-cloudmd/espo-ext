<?php

namespace Espo\Modules\SurveyMonkeySync\Core\Services;

use Espo\Core\Container;
use Espo\Modules\SurveyMonkeySync\Core\DataProviders\QuestionsAnswersProvider;

/**
 * @property QuestionData questionData
 * @property Container container
 * @property SurveyMonkeyService surveyMonkeyService
 * @property StoreSurveyResponse storeSurveyResponse
 * @property QuestionsAnswersProvider questionsAnswersProvider
 */
class SurveyResponsesSync
{
    /**
     * @var QuestionData
     */
    protected $questionData;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var SurveyMonkeyService
     */
    protected $surveyMonkeyService;

    /**
     * @var StoreSurveyResponse
     */
    protected $storeSurveyResponse;

    /**
     * @var QuestionsAnswersProvider
     */
    protected $questionsAnswersProvider;

    /**
     * SurveyResponsesSync constructor.
     * @param QuestionData $questionData
     * @param SurveyMonkeyService $surveyMonkeyService
     * @param Container $container
     * @param StoreSurveyResponse $storeSurveyResponse
     * @param QuestionsAnswersProvider $questionsAnswersProvider
     */
    public function __construct(
        QuestionData $questionData,
        SurveyMonkeyService $surveyMonkeyService,
        Container $container,
        StoreSurveyResponse $storeSurveyResponse,
        QuestionsAnswersProvider $questionsAnswersProvider
    )
    {
        $this->questionData = $questionData;
        $this->container = $container;
        $this->surveyMonkeyService = $surveyMonkeyService;
        $this->storeSurveyResponse = $storeSurveyResponse;
        $this->questionsAnswersProvider = $questionsAnswersProvider;
    }

    /**
     * @throws \Espo\Core\Exceptions\Error
     *
     * @return void
     */
    public function execute()
    {
        $syncLastMinutes = !empty($this->getIntegrationConfig()->get('syncLastMinutes')) ? $this->getIntegrationConfig()->get('syncLastMinutes') : 10;
        $lastSinceDate = (new \DateTime("-${syncLastMinutes} minutes"))->format('Y-m-d\TH:i:s.v');

        foreach($this->getSurveysId() as $surveyId) {
            $GLOBALS['log']->error('Start surveyId: ' . $surveyId);
            $page = 1;
            $perPage = 100;

            while(true) {
                $response = $this->surveyMonkeyService->getSurveyResponses($surveyId,[
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_order' => 'desc',
                    'start_created_at' => $lastSinceDate,
                    'status' => 'completed'
                ]);
                $this->syncResponses($surveyId,$response['data']);

                if(empty($response['links']['next'])) {
                    $GLOBALS['log']->error('Break End surveyId: ' . $surveyId);
                    break;
                }
                $page++;
            }

            $GLOBALS['log']->error('End surveyId: ' . $surveyId);
        }
    }

    /**
     * @param $surveyId
     * @param array $responses
     * @return null
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function syncResponses($surveyId,$responses = [])
    {
        $GLOBALS['log']->error('All data: ' . $surveyId, [
            'responses' => $responses
        ]);
        if (empty($responses)) {
            return;
        }
        foreach($responses as $response) {
            $GLOBALS['log']->error('Start sync, surveyId: ' . $surveyId, [
                'response' => $response
            ]);

            if (empty($response['pages'][0])) {
                throw new \Exception('PAGES_IS_EMPTY');
            }

            $page = $response['pages'][0];
            $this->questionData->surveyId = $surveyId;

            $questionData = $this->questionsAnswersProvider->getQuestionsData();
            $questionsData = array_values(array_filter($questionData,function($questionItem) use($surveyId){
                return $questionItem['id'] == $surveyId;
            }));

            if (empty($questionsData[0])) {
                return null;
            }

            $this->questionData->locale = $questionsData[0]['locale'];
            $questionsWithData = [];
            foreach($page['questions'] as $question) {
                $this->questionData->question = $question;
                $this->questionData->questionItem = $this->getQuestionItem($questionsData[0],$question['id']);
                $questionWithData = $this->questionData->getQuestionWithData();
                if(empty($questionWithData)) {
                    continue;
                }
                $questionsWithData[] = $questionWithData;
            }

            $this->storeSurveyResponse->questionsData = [
                'created_at' => $response['date_created'],
                'answers' => $questionsWithData,
                'survey_id' => $surveyId,
                'answer_id' => $response['id'],
                'name' => $questionsData[0]['name'],
                'locale' => $questionsData[0]['locale'],
                'entity' => $questionsData[0]['entity'],
                'contactRelation' => $questionsData[0]['contactRelation']
            ];
            $this->storeSurveyResponse->execute();

            $GLOBALS['log']->error('End sync, surveyId: ' . $surveyId,[
                'response' => [
                    'created_at' => $response['date_created'],
                    'answers' => $questionsWithData,
                    'surveyId' => $surveyId
                ]
            ]);
        }
    }

    /**
     * @param $surveyId
     * @return mixed|null
     */
    protected function getCurrentSurvey($surveyId)
    {
        $surveys = [
            [
                'key' => 'en_1',
                'locale' => 'en',
                'version' => 1,
                'id' => '314152476'
            ],
            [
                'key' => 'en_2',
                'locale' => 'en',
                'version' => 2,
                'id' => '314153928'
            ]
        ];

        $survey = array_values(array_filter($surveys,function($surveyItem) use($surveyId){
            return $surveyItem['id'] === $surveyId;
        }));

        if (empty($survey)) {
            return null;
        }

        return $survey[0];
    }

    /**
     * @return array
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getSurveysId()
    {
        $integration = $this->getIntegrationConfig();
        $surveyIds = [];
        $surveyIdsKey = [
            'version_1_en',
            'version_1_fr',
            'version_2_en',
            'version_2_fr'
        ];
        foreach($surveyIdsKey as $surveyIdKey) {
            $surveyId = $integration->get($surveyIdKey);
            if (!empty($surveyId)) {
                $surveyIds[] = $surveyId;
            }
        }

        return $surveyIds;
    }

    /**
     * @param $questionsData
     * @param $questionId
     * @return mixed|null
     */
    protected function getQuestionItem($questionsData, $questionId)
    {
        $questionItems = array_values(array_filter($questionsData['questions'],function($questionItem) use($questionId){
            return $questionItem['id'] == $questionId;
        }));

        if (empty($questionItems[0])) {
            return null;
        }

        return $questionItems[0];
    }

    /**
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getIntegrationConfig()
    {
        return $this->container->get('entityManager')->getEntity('Integration', 'SurveyMonkey');
    }

}