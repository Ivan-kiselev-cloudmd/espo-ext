<?php

namespace Espo\Modules\SurveyEntitySync\Jobs;

use Espo\Core\Container;
use Espo\Modules\SurveyEntitySync\Core\Synchronization\BaseReassessmentSynchronization;
use Espo\Modules\SurveyMonkeySync\Core\Services\SurveyResponsesSync;

/**
 * @property SurveyResponsesSync surveyResponsesSync
 */
class RiskFactorSurveyMonkeySyncJob extends \Espo\Core\Jobs\Base
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var BaseReassessmentSynchronization
     */
    protected $baseReassessmentSynchronization;

    /**
     * @param Container $container
     * @param BaseReassessmentSynchronization $baseReassessmentSynchronization
     */
    public function __construct(
        Container $container,
        BaseReassessmentSynchronization $baseReassessmentSynchronization
    )
    {
        parent::__construct($container);
        $this->container = $container;
        $this->baseReassessmentSynchronization = $baseReassessmentSynchronization;
    }

    /**
     * @param $data
     * @param $targetId
     * @throws \Espo\Core\Exceptions\Error
     */
    public function run($data, $targetId)
    {
        $GLOBALS['log']->error('---------Start-----------');
        $this->baseReassessmentSynchronization->sync();
        $GLOBALS['log']->error('---------End-----------');
    }


}