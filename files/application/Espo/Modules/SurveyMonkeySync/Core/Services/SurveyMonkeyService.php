<?php

namespace Espo\Modules\SurveyMonkeySync\Core\Services;

require_once __DIR__ . './../../vendor/autoload.php';

use Espo\Core\Container;

/**
 * @property QuestionData questionData
 * @property Container container
 */
class SurveyMonkeyService
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * SurveyMonkeyService constructor.
     * @param Container $container
     */
    public function __construct(
        Container $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param $surveyId
     * @param array $filters
     * @return array|false|mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    public function getSurveyResponses($surveyId,$filters = [])
    {
        $client = $this->getClient();
        $response = $client->getSurveyResponsesBulk($surveyId,$filters);

        return $response->getData();
    }

    /**
     * @param $surveyId
     * @param array $additionalData
     * @return array|false|mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    public function createSurveyCollector($surveyId, $additionalData = [])
    {
        $client = $this->getClient();
        $response = $client->createCollectorForSurvey($surveyId,array_merge($additionalData,[
            'type' => 'weblink'
        ]));

        $GLOBALS['log']->info("Response Survey monkey [${$surveyId}]",[
            'surveyId' => $surveyId,
            'additionalData' => $additionalData,
            'is_error' => $response->isError(),
            'is_success' => $response->isSuccess(),
            'error' => $response->getError(),
            'data' => $response->getData() 
        ]);

        return $response->getData();
    }

    /**
     * @return \Spliced\SurveyMonkey\Client
     * @throws \Espo\Core\Exceptions\Error
     */
    private function getClient()
    {
        $integration = $this->container->get('entityManager')->getEntity('Integration', 'SurveyMonkey');
        $surveyApiKey = $integration->get('surveyApiKey');
        $surveyAccessToken = $integration->get('surveyAccessToken');

        return new \Spliced\SurveyMonkey\Client($surveyApiKey, $surveyAccessToken);
    }

}