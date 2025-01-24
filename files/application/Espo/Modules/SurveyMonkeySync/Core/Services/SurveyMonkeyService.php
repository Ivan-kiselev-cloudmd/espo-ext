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