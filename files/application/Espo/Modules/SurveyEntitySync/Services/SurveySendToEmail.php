<?php

namespace Espo\Modules\SurveyEntitySync\Services;

use Espo\Services\Email;
use stdClass;

class SurveySendToEmail extends \Espo\Core\Templates\Services\Base
{
    /**
     * @var Email
     */
    protected $emailService;

    /**
     * @param Email $emailService
     */
    public function __construct(
        Email $emailService
    )
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * @param $contact
     * @param $surveyMonkeyCollector
     * @return void
     * @throws \Exception
     */
    public function send($contact, $surveyMonkeyCollector)
    {
        try {
            $config = $this->config;

            $data = new StdClass();
            $data->from = $config->get('smtpUsername');
            $data->to = $contact->get('emailAddress');
            $data->body = $surveyMonkeyCollector->get('collectorUrl');
            $data->isHtml = true;
            $data->name = 'Survey monkey';
            $data->status = 'Sending';
            $data->subject = 'Survey monkey';

            $this->emailService->create($data);
        } catch (\Exception $ex) {
            $GLOBALS['log']->error('Email survey send exception',[
                'message' => $ex->getMessage(),
                'contact' => $contact->get('id'),
                'surveyMonkeyCollector' => $surveyMonkeyCollector->get('id'),
                'error' => [
                    'message' => $ex->getMessage(),
                    'line' => $ex->getLine(),
                    'file' => $ex->getFile(),
                    'full' => $ex->getTrace()
                ]
            ]);
            throw new \Exception($ex->getMessage());
        }
    }

}