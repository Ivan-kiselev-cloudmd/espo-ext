<?php

namespace Espo\Modules\SnapclaritySync\Core\Report;

use Espo\Modules\SnapclaritySync\Core\Report\Contracts\APIErrorReportable;
use Espo\Modules\SnapclaritySync\Core\Report\Contracts\EmailReportable;
use Espo\Modules\SnapclaritySync\Core\Report\Contracts\LogFileReportable;
use Espo\ORM\EntityManager;
use Espo\Services\Email;
use stdClass;

/**
 * @property EntityManager entityManager
 * @property Email emailService
 */
class ErrorReport implements EmailReportable, APIErrorReportable, LogFileReportable
{

    /**
     * @var array
     */
    protected $errorData = [];

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Email
     */
    protected $emailService;

    /**
     * ErrorReport constructor.
     * @param EntityManager $entityManager
     * @param Email $emailService
     */
    public function __construct
    (
        EntityManager $entityManager,
        Email $emailService
    )
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    /**
     * @param array $errorData
     * @return $this|APIErrorReportable|ErrorReport|mixed
     */
    public function setErrorData(array $errorData = [])
    {
        $this->errorData = $errorData;
        return $this;
    }

    /**
     * @param array $emails
     * @return EmailReportable|mixed|void
     */
    public function reportToEmail(array $emails = [])
    {
        $emailEntity = $this->entityManager->getRepository('InboundEmail')
            ->where([
                'status' => 'Active',
                'deleted' => 0
            ])->findOne();

        if (empty($emailEntity) || empty($emailEntity->get('emailAddress'))) {
            return;
        }

        try {
            $ccEmails = $emails;
            unset($ccEmails[0]);

            $data = new StdClass();
            $data->from = $emailEntity->get('emailAddress');
            $data->to = $emails[0];
            $data->body = '<pre><code>' . json_encode($this->errorData) .'</code></pre>';
            $data->cc = implode(';',$ccEmails);
            $data->isHtml = true;
            $data->name = 'API error report';
            $data->status = 'Sending';
            $data->subject = 'API error report';

            $this->emailService->create($data);
        } catch (\Exception $ex) {
            $GLOBALS['log']->error('Email report exception',[
                'message' => $ex->getMessage(),
                'errorData' => $this->errorData,
                'error' => [
                    'message' => $ex->getMessage(),
                    'line' => $ex->getLine(),
                    'file' => $ex->getFile(),
                    'full' => $ex->getTrace()
                ]
            ]);
        }
    }

    /**
     * @return mixed|void
     */
    public function reportToLogFile()
    {
        $GLOBALS['log']->error('Error Report',$this->errorData);
    }

}