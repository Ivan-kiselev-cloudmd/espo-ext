<?php

namespace Espo\Modules\SurveyMonkeySync\Jobs;

use Espo\Core\Container;
use Espo\Modules\SurveyMonkeySync\Core\Services\SurveyResponsesSync;

/**
 * @property SurveyResponsesSync surveyResponsesSync
 */
class SurveyMonkeyJob extends \Espo\Core\Jobs\Base
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var SurveyResponsesSync
     */
    protected $surveyResponsesSync;

    /**
     * SurveyMonkeyJob constructor.
     * @param Container $container
     * @param SurveyResponsesSync $surveyResponsesSync
     */
    public function __construct(
        Container $container,
        SurveyResponsesSync $surveyResponsesSync
    )
    {
        parent::__construct($container);
        $this->container = $container;
        $this->surveyResponsesSync = $surveyResponsesSync;
    }

    /**
     * @param $data
     * @param $targetId
     * @throws \Espo\Core\Exceptions\Error
     */
    public function run($data, $targetId)
    {
        $GLOBALS['log']->error('---------Start-----------');
        $this->surveyResponsesSync->execute();
        $GLOBALS['log']->error('---------End-----------');
    }


}