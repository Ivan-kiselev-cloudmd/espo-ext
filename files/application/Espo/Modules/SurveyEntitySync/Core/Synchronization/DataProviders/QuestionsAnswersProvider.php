<?php

namespace Espo\Modules\SurveyEntitySync\Core\Synchronization\DataProviders;

use Espo\Core\Container;
use Espo\Core\Utils\Metadata;
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

/**
 * @property Container container
 */
class QuestionsAnswersProvider
{
    const ENVIRONMENT_DEVELOPMENT = 'development';
    const ENVIRONMENT_PRODUCTION = 'production';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * QuestionsAnswersProvider constructor.
     * @param Container $container
     * @param Metadata $metadata
     */
    public function __construct(
        Container $container,
        Metadata $metadata
    )
    {
        $this->metadata = $metadata;
        $this->container = $container;
    }

    /**
     * @return array[]
     * @throws \Espo\Core\Exceptions\Error
     */
    public function getQuestionsData()
    {
        $developmentMode = $this->getDevelopmentMode();

        $surveyEntities = [
//            ReassessmentGAD::class,
//            ReassessmentADHD::class,
//            ReassessmentAlcoholUseDisorder::class,
//            ReassessmentAngerManagement::class,
//            ReassessmentBipolar::class,
//            ReassessmentDepression::class,
//            ReassessmentEatingDisorder::class,
//            ReassessmentOCD::class,
//            ReassessmentPanicDisorder::class,
//            ReassessmentPTSD::class,
//            ReassessmentSAD::class,
//            ReassessmentSleepDisorder::class,
//            ReassessmentSUD::class,
            SatisfactionSurvey::class,
            SatisfactionSurveyYAP::class
        ];

        $surveys = [];
        foreach($surveyEntities as $surveyEntity) {
            $namespaces = explode("\\",$surveyEntity);
            if (!count($namespaces)) {
                continue;
            }
            $className = $namespaces[count($namespaces) - 1];
            $surveyEntityBaseData = $this->metadata->get(['surveyEntities', $className]);
            $survey = $this->metadata->get(['surveyEntities', $className,'surveys', $developmentMode]);
            if (!empty($survey)) {
                $surveys[$surveyEntity] = [
                    'relations' => $surveyEntityBaseData['relations'],
                    'entity' => $surveyEntityBaseData['entity'],
                    'survey' => $survey
                ];
            }
        }

        return $surveys;
    }

    /**
     * @return string
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getDevelopmentMode()
    {
        $integration = $this->container->get('entityManager')->getEntity('Integration', 'SurveyMonkey');

        return !empty($integration->get('isProduction')) ? self::ENVIRONMENT_PRODUCTION : self::ENVIRONMENT_DEVELOPMENT;
    }
}