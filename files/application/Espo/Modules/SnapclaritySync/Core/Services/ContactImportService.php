<?php

namespace Espo\Modules\SnapclaritySync\Core\Services;

use Espo\ORM\Entity;
use Espo\Modules\SnapclaritySync\Core\Events\ContactCreateEvent;
use Espo\Modules\SnapclaritySync\Core\Events\ContactUpdateEvent;

class ContactImportService extends BaseImportService
{
    /**
     * @var
     */
    protected $changeAssessmentProgressExist = false;

    /**
     * @return bool
     */
    public function isChangeAssessmentProgressExist()
    {
        return $this->changeAssessmentProgressExist;
    }

    /**
     * @return $this
     */
    public function import()
    {
        $contactData = $this->baseData;
        $entityItem = $this->entityManager->getRepository($this->getEntityName())->where([
            'caseid' => $contactData['_id']
        ])->findOne();

        $isCreate = false;
        if (empty($entityItem)) {
            $isCreate = true;
            /** @var $entityItem Entity */
            $entityItem = $this->entityManager->getRepository($this->getEntityName())->get();
        }

        $assessmentReportUrl = '';
        if (!empty($contactData['assessment_report_url'])) {
            $assessmentReportUrl = $this->getReportReplacedUrl($contactData['assessment_report_url']);
        }

        $account = null;
        if (!empty($contactData['organization'])) {
            $account = $this->entityManager->getRepository('Account')->where([
                'snapclarityOrganizationId' => $contactData['organization'],
                'deleted' => 0
            ])->findOne();
        }

        $lang = '';
        if (!empty($contactData['lang']) && !empty(explode('-',$contactData['lang'])[0])) {
            $lang = explode('-',$contactData['lang'])[0];
        }

        $this->changeAssessmentProgressExist = false;
        if (!empty($contactData['assessment_progress']) && $contactData['assessment_progress'] == 1 && ($isCreate || $entityItem->get('assessmentProgress') != 1)) {
            $this->changeAssessmentProgressExist = true;
        }

        $fullName = explode(' ', $contactData['name']);
        $saveData = [
            'accountId' => !empty($account) ? $account->get('id') : null,
            'emailAddress' => !empty($contactData['email']) ?  strtolower($contactData['email']) : null,
            'name' => !empty($contactData['name']) ? $contactData['name'] : null,
            'firstName' => !empty($fullName[0]) ? $fullName[0] : null,
            'lastName' => !empty($fullName[1]) ? $fullName[1] : null,
            'dob' => !empty($contactData['dob']) ? $contactData['dob'] : null,
            'addressPostalCode' => !empty($contactData['postalCode']) ?  $contactData['postalCode'] : null,
            'caseid' => $contactData['_id'],
            'memberCode' => !empty($contactData['memberCode']) ?  $contactData['memberCode'] : null,
            'addressStreet' => !empty($contactData['address']) ? $contactData['address'] : null,
            'addressState' => !empty($contactData['province']) ? $contactData['province'] : null,
            'addressCity' => !empty($contactData['city']) ?  $contactData['city'] : null,
            'employment' => !empty($contactData['employment']) ? $contactData['employment'] : null,
            'gender' => !empty($contactData['gender']) ? $contactData['gender'] : null,
            'lang' => $lang,
            'region' => !empty($contactData['region']) ? $contactData['region'] : null,
            'assessmentReportUrl' => $assessmentReportUrl,
            'completeAssessmentChoice' => !empty($contactData['followupOption']) ? $contactData['followupOption'] : null,
            'phoneNumber' => !empty($contactData['phone'])  ? $contactData['phone'] : null,
            'assessmentQuestion' => !empty($contactData['assessment_question'])  ? $contactData['assessment_question'] : null,
            'assessmentProgress' => !empty($contactData['assessment_progress'])  ? $contactData['assessment_progress'] : null,
            'assessmentFirstAnswerDate' => !empty($contactData['assessment_first_answer_date']) ? gmdate('Y-m-d H:i:s', strtotime($contactData['assessment_first_answer_date'])) : null,
            'assessmentLastAnswerDate' => !empty($contactData['assessment_last_answer_date'])  ? gmdate('Y-m-d H:i:s', strtotime($contactData['assessment_last_answer_date'])) : null,
            'requestServiceAs' => !empty($contactData['requestServiceAs']) ?  $contactData['requestServiceAs'] : null,
            'dateOfRegistration' => !empty($contactData['created_at']) ? date_create($contactData['created_at'])->format('Y-m-d') : null,
            'kiiOrganizationId' => !empty($contactData['kii_organization_id']) ? $contactData['kii_organization_id'] : null,
        ];

        if ($isCreate) {
            $saveData['createdById'] = 1;
        }

        $response = $this->saveEntityUpdatedData($entityItem,$saveData,$isCreate);
        $entityItem = $response['entity'];

        $this->saveOriginalEmail($entityItem, $contactData);

        $this->setContact($entityItem);

        if ($isCreate) {
            new ContactCreateEvent($this->container,$this->entityManager,$this->metadata,$entityItem,$contactData, ['changeAssessmentProgressExist' => $this->changeAssessmentProgressExist]);
        } else {
            new ContactUpdateEvent($this->container,$this->entityManager,$this->metadata,$entityItem,$contactData, ['changeAssessmentProgressExist' => $this->changeAssessmentProgressExist]);
        }

        $GLOBALS['log']->warning($contactData['_id'],[
            'update_or_create' => $response['mustInsert'],
            'is_create' => $isCreate,
            'contact_id' => $contactData['_id']
        ]);

        return $this;
    }

    /**
     * @param $entityItem
     * @param array $contactData
     */
    protected function saveOriginalEmail($entityItem,$contactData = [])
    {
        if (!empty($contactData['original_email']) && $contactData['email'] !== $contactData['original_email']) {
            $originalEmail = $contactData['original_email'];
            /** @var $emails \Espo\ORM\EntityCollection */
            $emails = $this->entityManager->getRepository('EntityEmailAddress')
                ->where([
                    'entityId' => $entityItem->get('id'),
                    'primary' => 0
                ])->find();

            if ($emails->count() == 0) {
                $entityEmail = $this->entityManager->getRepository('EmailAddress')->get();
                $entityEmail->set([
                    'name' => $originalEmail,
                    'lower' => strtolower($originalEmail),
                    'invalid' => 0,
                    'optOut' => 0
                ]);
                $this->entityManager->saveEntity($entityEmail);

                $entityEmailAddress = $this->entityManager->getRepository('EntityEmailAddress')->get();
                $entityEmailAddress->set([
                    'entityId' => $entityItem->get('id'),
                    'emailAddressId' => $entityEmail->get('id'),
                    'entityType' => $this->getEntityName(),
                    'primary' => 0
                ]);
                $this->entityManager->saveEntity($entityEmailAddress);
            }
        }
    }

    /**
     * @param $assessmentReportUrl
     * @return array|string|string[]
     * @throws \Espo\Core\Exceptions\Error
     */
    protected function getReportReplacedUrl($assessmentReportUrl)
    {
        $integration = $this->container->get('entityManager')->getEntity('Integration', 'Contact');

        if ($integration->get('isProduction')) {
            $assessmentReplacedReportUrl = str_replace('https://snapclaritystorage.blob.core.windows.net/','https://slprod.snapclarity.net/api/v0/blob/', $assessmentReportUrl);
        } else {
            $assessmentReplacedReportUrl = str_replace('https://medstackstoragekhhhveuhp.blob.core.windows.net/','https://staging.snapclarity.net/api/v0/blob/', $assessmentReportUrl);
        }

        return $assessmentReplacedReportUrl;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Contact';
    }
}