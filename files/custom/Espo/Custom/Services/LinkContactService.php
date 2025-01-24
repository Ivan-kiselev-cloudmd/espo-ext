<?php

namespace Espo\Custom\Services;

use DateInterval;
use DateTime;
use Espo\Core\Utils\Metadata;
use Espo\Modules\MedicalConfidence\Core\DataProviders\ContactCoachAppointments;
use Espo\Modules\MedicalConfidence\Core\DataProviders\PhoneEmailRegex;
use \Espo\ORM\Entity;
use Espo\ORM\EntityManager;
use Espo\Modules\MedicalConfidence\Services\Contact as ContactService;

/**
 * @property Entity entity
 * @property Entity contactEntity
 * @property Metadata metadata
 * @property array matchedValues
 */
class LinkContactService extends \Espo\Core\Services\Base
{

    /**
     * Match types.
     */
    const MATCH_TYPE_EMAIL = 'email';
    const MATCH_TYPE_PHONE = 'phone';

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var
     */
    protected $contactEntity;

    /**
     * @var
     */
    protected $matchedValues = [
        self::MATCH_TYPE_PHONE => [],
        self::MATCH_TYPE_EMAIL => []
    ];

    /**
     * @var \string[][]
     */
    protected $coachAppointments = [];

    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * @var \Espo\ORM\Repository\Repository
     */
    protected $contactRepository;

    /**
     * @var \Espo\ORM\Repository\Repository
     */
    protected $meetingRepository;

    /**
     * @var ContactCoachAppointments
     */
    protected $contactCoachAppointments;

    /**
     * @var PhoneEmailRegex
     */
    protected $phoneEmailRegex;

    /**
     * @var string
     */
    private $foundedMatchType = '';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * LinkContactService constructor.
     * @param EntityManager $entityManager
     * @param Metadata $metadata
     * @param ContactService $contactService
     * @param ContactCoachAppointments $contactCoachAppointments
     */
    public function __construct(
        EntityManager $entityManager,
        ContactService $contactService,
        ContactCoachAppointments $contactCoachAppointments,
        PhoneEmailRegex $phoneEmailRegex
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->contactService = $contactService;
        $this->contactRepository = $this->entityManager->getRepository('Contact');
        $this->meetingRepository = $this->entityManager->getRepository('Meeting');
        $this->contactCoachAppointments = $contactCoachAppointments;
        $this->phoneEmailRegex = $phoneEmailRegex;
    }


    /**
     * @param $workflowId
     * @param Entity $entity
     * @param null $additionalParameters
     * @throws \Exception
     */
    public function linkMeetingToContact($workflowId, Entity $entity, $additionalParameters = null)
    {
        if (empty($entity->get('dateStart'))) {
            return;
        }
        $transaction = $this->entityManager->getTransactionManager();
        $this->entity = $entity;
        $this->findContact();

        $transaction->start();

        if ($this->foundedMatchType === self::MATCH_TYPE_PHONE && !empty($this->contactEntity) && count($this->matchedValues[self::MATCH_TYPE_EMAIL]) > 0) {
            $this->contactRepository->insertNewEmails($this->contactEntity,$this->matchedValues[self::MATCH_TYPE_EMAIL]);
        }
        if (empty($this->contactEntity)) {
            return;
        }

        // Relate Meeting to contact
        $this->meetingRepository->getRelation($entity, 'contacts')->relate($this->contactEntity);

        $coachAppointments = $this->contactCoachAppointments->filterByDateAndGetCoachAppointment($this->contactEntity, $entity);
        // If exist coach appointments update this columns.
        if (count($coachAppointments) > 0) {
            foreach ($coachAppointments['columns'] as $column) {
                $this->contactEntity->set($column['name'], $column['default']);
            }
            $this->entityManager->saveEntity($this->contactEntity);
        }

        $transaction->commit();
    }

    /**
     * Match email or phone in description and find contact.
     *
     * @return void
     */
    private function findContact()
    {
        $this->matchedValues = $this->phoneEmailRegex->matchDescription($this->entity->get('description'));
        $matchColumnTypes = [
            [
                'key' => self::MATCH_TYPE_EMAIL,
                'relation' => 'emailAddress',
            ],
            [
                'key' => self::MATCH_TYPE_PHONE,
                'relation' => 'phoneNumber',
            ]
        ];

        $matchColumnTypesQty = count($matchColumnTypes);
        $matchColumnIndex = 0;
        while (empty($this->contactEntity) && $matchColumnIndex < $matchColumnTypesQty) {
            $matchColumn = $matchColumnTypes[$matchColumnIndex];
            $mappingFunction = "findBy" . ucfirst($matchColumn['relation']);
            $contact = $this->contactService->{$mappingFunction}($this->matchedValues[$matchColumn['key']]);
            if ($contact) {
                $this->foundedMatchType = $matchColumn['key'];
                $this->contactEntity = $contact;
            }
            $matchColumnIndex++;
        }
    }

}
