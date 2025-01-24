<?php

namespace Espo\Modules\SurveyEntitySync\Services;

use Espo\Core\Container;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\BaseReassessmentSynchronization;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\SurveyQuestionsFieldsParser;
use Espo\ORM\Entity;
use Espo\ORM\Metadata;

class AssessmentSurveySyncService extends \Espo\Core\Templates\Services\Base
{
    /**
     * @var BaseReassessmentSynchronization
     */
    protected $baseReassessmentSynchronization;

    /**
     * @var SurveyQuestionsFieldsParser
     */
    protected $surveyQuestionsFieldsParser;

    /**
     * @param BaseReassessmentSynchronization $baseReassessmentSynchronization
     * @param SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser
     */
    public function __construct(
        BaseReassessmentSynchronization $baseReassessmentSynchronization,
        SurveyQuestionsFieldsParser $surveyQuestionsFieldsParser
    )
    {
        parent::__construct();
        $this->baseReassessmentSynchronization = $baseReassessmentSynchronization;
        $this->surveyQuestionsFieldsParser = $surveyQuestionsFieldsParser;
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
        $this->baseReassessmentSynchronization->sync();
    }
}