<?php

namespace Espo\Modules\SurveyEntitySync\Controllers;

use Espo\Core\Container;
use Espo\Core\Exceptions\BadRequest;
use Espo\Core\Exceptions\Forbidden;
use Espo\Modules\SurveyEntitySync\Core\Services\ReAssessmentSurveyMonkeyCollectorIdSave;
use Espo\Modules\SurveyEntitySync\Services\SatisfactionSurveyService;
use Espo\Modules\SurveyMonkeySync\Core\Services\SurveyMonkeyService;
use Espo\ORM\Entity;

class SurveyMonkeyGenerate extends \Espo\Core\Controllers\Base
{
    /**
     * @var SatisfactionSurveyService
     */
    protected $satisfactionSurveyService;

    /**
     * @param Container $container
     * @param SatisfactionSurveyService $satisfactionSurveyService
     */
    public function __construct(
        Container $container,
        SatisfactionSurveyService $satisfactionSurveyService
    )
    {
        parent::__construct($container);
        $this->satisfactionSurveyService = $satisfactionSurveyService;
    }

    /**
     * @param $params
     * @param $data
     * @param $request
     * @return bool[]
     */
    public function postActionSatisfactionGenerate($params, $data, $request)
    {
        if (!$this->getAcl()->checkScope($this->name)) throw new Forbidden();
        if (empty($data->id)) throw new BadRequest();

        try {
            $contactId = $data->id;

            $contact = $this->getContainer()->get('entityManager')->getRepository('Contact')->where([
                'id' => $contactId
            ])->findOne();

            $this->satisfactionSurveyService->generateSurvey($contact);

            return [
                'success' => true
            ];
        } catch (\Exception $ex) {
            throw new BadRequest();
        }
    }

}