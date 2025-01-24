<?php


namespace Espo\Modules\SnapclaritySync\Services;


use Espo\Core\Container;
use Espo\Core\Utils\Metadata;
use Espo\Modules\SnapclaritySync\Core\Gateways\SnapclarityGateway;
use Espo\Modules\SnapclaritySync\Core\Report\Contracts\EmailReportable;
use Espo\Modules\SnapclaritySync\Core\Report\Contracts\LogFileReportable;
use Espo\Modules\SnapclaritySync\Core\Report\ErrorReport;
use Espo\Modules\SnapclaritySync\Core\Services\AssessmentScoreImportService;
use Espo\Modules\SnapclaritySync\Core\Services\CheckpointsImportService;
use Espo\Modules\SnapclaritySync\Core\Services\ContactImportService;
use Espo\Modules\SnapclaritySync\Core\Services\Contracts\EntityDataSetable;
use Espo\Modules\SnapclaritySync\Core\Services\TargetedAssessmentImportService;
use Espo\ORM\Entity;
use Espo\ORM\EntityManager;

/**
 * @property SnapclarityGateway snapclarityGateway
 * @property ContactImportService contactImportService
 * @property ContactImportService[] entityImportProcessors
 * @property ErrorReport errorReport
 * @property int|mixed syncLastMinutes
 */
class ContactSync extends \Espo\Core\Templates\Services\Base
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var SnapclarityGateway
     */
    protected $snapclarityGateway;

    /**
     * @var ContactImportService[]
     */
    protected $entityImportProcessors = [];

    /**
     * @var ErrorReport
     */
    protected $errorReport;

    /**
     * @var Entity
     */
    private $contact;

    /**
     * @var array
     */
    private $reportEmails = [];

    /**
     * @var
     */
    private $uniqueId;

    /**
     * @var
     */
    private $syncLastMinutes;

    /**
     * ContactSync constructor.
     * @param Container $container
     * @param EntityManager $entityManager
     * @param Metadata $metadata
     * @param SnapclarityGateway $snapclarityGateway
     * @param ContactImportService $contactImportService
     * @param CheckpointsImportService $checkpointsImportService
     * @param AssessmentScoreImportService $assessmentScoreImportService
     * @param TargetedAssessmentImportService $targetedAssessmentImportService
     * @param ErrorReport $errorReport
     */
    public function __construct(
        Container $container,
        EntityManager $entityManager,
        Metadata $metadata,
        SnapclarityGateway $snapclarityGateway,
        ContactImportService $contactImportService,
        CheckpointsImportService $checkpointsImportService,
        AssessmentScoreImportService $assessmentScoreImportService,
        TargetedAssessmentImportService $targetedAssessmentImportService,
        ErrorReport $errorReport
    )
    {
        parent::__construct();
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
        $this->snapclarityGateway = $snapclarityGateway;
        $this->entityImportProcessors = [
            'contact' => $contactImportService,
            'checkpoints' => $checkpointsImportService,
            'assessmentScore' => $assessmentScoreImportService,
            'targetedAssessment' => $targetedAssessmentImportService
        ];
        $this->errorReport = $errorReport;
    }

    /**
     * @param null $additionalParameters
     */
    public function syncContactWithRelateEntitiesFromAPI($additionalParameters = null)
    {
        $this->syncLastMinutes = !empty($additionalParameters) && property_exists($additionalParameters, 'syncLastMinutes') ? $additionalParameters->syncLastMinutes : 10;
        $this->reportEmails = !empty($additionalParameters) && property_exists($additionalParameters, 'reportEmails') ? $additionalParameters->reportEmails : [];
        $limit = 100;
        $skip = 0;
        $syncLastMinutes = $this->syncLastMinutes;
        $lastSinceDate = (new \DateTime("-${syncLastMinutes} minutes"))->format('Y-m-d\TH:i:s.v') . 'Z';
        $organizationsId = !empty($this->getIntegration()->get('organizationsId')) ? $this->getIntegration()->get('organizationsId') : [];

        foreach($organizationsId as $organizationId) {
            $existContacts = true;
            while ($existContacts) {
                $this->uniqueId = uniqid('REQUEST_');
                $requestData = [
                    'limit' => $limit,
                    'skip' => $skip,
                    'since' => $lastSinceDate,
                    'organization' => $organizationId
                ];
                $snapclarityGatewayResponse = $this->snapclarityGateway->getContacts($requestData);

                $GLOBALS['log']->warning($this->uniqueId, [
                    'message' => 'sent request to snap api',
                    'limit' => $limit,
                    'skip' => $skip,
                    'since' => $lastSinceDate,
                    'organization' => $organizationId
                ]);

                if ($snapclarityGatewayResponse['success'] === false) {
                    $this->reportError([
                        'message' => "Workflow[ContactSync]: limit: {$limit}, skip: {$skip}",
                        'request_data' => $requestData,
                        'api_response' => $snapclarityGatewayResponse
                    ]);
                    break;
                }

                $apiContactsData = $snapclarityGatewayResponse['data']['users'];
                if (count($apiContactsData) === 0) {
                    $GLOBALS['log']->warning($this->uniqueId, [
                        'message' => 'sent request to snap api',
                        'total_contacts_qty' => 0,
                        'limit' => $limit,
                        'skip' => $skip,
                        'since' => $lastSinceDate,
                        'organization' => $organizationId
                    ]);
                    $existContacts = false;
                    continue;
                }

                $GLOBALS['log']->warning($this->uniqueId, [
                    'total_contacts_qty' => count($apiContactsData)
                ]);

                $this->importContacts($organizationId,$apiContactsData);
                $skip += $limit;
            }
        }
    }

    /**
     * @param $organizationId
     * @param array $contactsData
     */
    private function importContacts($organizationId,array $contactsData = [])
    {
        $transaction = $this->entityManager->getTransactionManager();
        foreach ($contactsData as $contactData) {
            if (strtotime("-".$this->syncLastMinutes." minutes") > strtotime($contactData['changed_at'])) {
                $GLOBALS['log']->warning($this->uniqueId,[
                    'contact_id' => $contactData['_id'],
                    'message' => 'Not update because update at is past',
                    'updated_at' => $contactData['changed_at']
                ]);
                continue;
            }

            $transaction->start();

            try {
                $contactData['organization'] = $organizationId;
                $this->contact = $this->entityImportProcessors['contact']
                    ->setBaseData($contactData)->import()->getContact();
                if ($this->contact->get('deleted') == 1) {
                    continue;
                }
                $this->syncCheckpoints(!empty($contactData['checkpoints']) ? $contactData['checkpoints'] : []);
                $this->syncAssessmentDiagnosis(!empty($contactData['assessment_diagnosis']) ? $contactData['assessment_diagnosis'] : []);
                $this->syncTargetedAssessment([
                    'assessments' => !empty($contactData['assessments']) ? $contactData['assessments'] : [],
                    'checkpoints' => !empty($contactData['checkpoints']) ? $contactData['checkpoints'] : []
                ]);

                $transaction->commit();

                $GLOBALS['log']->warning($this->uniqueId,[
                    'contact_id' => $contactData['_id']
                ]);
            } catch (\Exception $ex) {
                $GLOBALS['log']->warning($this->uniqueId,[
                    'contact_id' => $contactData['_id'],
                    'error' => $ex->getMessage()
                ]);
                $transaction->rollback();
                $this->reportError([
                    'errorMessage' => $ex->getMessage(),
                    'step' => "Workflow[ContactSync Entity]: [contactId] - " . $contactData['_id'],
                    'snapId' => $contactData['_id'],
                    'snapData' => $contactData,
                    'error' => [
                        'message' => $ex->getMessage(),
                        'line' => $ex->getLine(),
                        'file' => $ex->getFile(),
                        'full' => $ex->getTrace()
                    ]
                ]);
            }
        }
    }

    /**
     * @param array $assessmentsData
     */
    private function syncTargetedAssessment($assessmentsData = [])
    {
        if (!empty($assessmentsData['assessments'][0]['questions'])) {
            $targetedAssessmentImportService = $this->entityImportProcessors['targetedAssessment']->setContact($this->contact);
            if ($targetedAssessmentImportService instanceof EntityDataSetable) {
                $targetedAssessmentImportService = $targetedAssessmentImportService->setEntityData([
                    'questions' => $assessmentsData['assessments'][0]['questions'],
                    'checkpoints' => $assessmentsData['checkpoints']
                ]);
            }
            $targetedAssessmentImportService->import();
        }
    }

    /**
     * @param $assessmentDiagnosisData
     */
    private function syncAssessmentDiagnosis($assessmentDiagnosisData)
    {
        if (!empty($assessmentDiagnosisData)) {
            if (!empty($assessmentDiagnosisData['scores'])) {
                $assessmentScoreImportService = $this->entityImportProcessors['assessmentScore']->setContact($this->contact);
                foreach($assessmentDiagnosisData['scores'] as $scoreData) {

                    if ($assessmentScoreImportService instanceof EntityDataSetable) {
                        $assessmentScoreImportService = $assessmentScoreImportService->setEntityData([
                            'data' => $scoreData
                        ]);
                    }
                    $assessmentScoreImportService->import();
                }

            }

        }
    }

    /**
     * @param $checkpoints
     */
    private function syncCheckpoints($checkpoints)
    {
        if (!empty($checkpoints)) {
            $checkpointsImportService = $this->entityImportProcessors['checkpoints']->setContact($this->contact);
            if ($checkpointsImportService instanceof EntityDataSetable) {
                $checkpointsImportService = $checkpointsImportService->setEntityData($checkpoints);
            }
            $checkpointsImportService->import();
        }
    }

    /**
     * @param array $errorData
     */
    private function reportError(array $errorData = [])
    {
        $this->errorReport->setErrorData($errorData);

        if (!empty($this->reportEmails) && $this->errorReport instanceof EmailReportable) {
            $this->errorReport->reportToEmail($this->reportEmails);
        } else if ($this->errorReport instanceof LogFileReportable) {
            $this->errorReport->reportToLogFile();
        }
    }

    /**
     * @return mixed
     * @throws \Espo\Core\Exceptions\Error
     */
    private function getIntegration()
    {
        return $this->container->get('entityManager')->getEntity('Integration', 'Contact');
    }

}