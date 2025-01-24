<?php

namespace Espo\Modules\SnapclaritySync\Core\Events;

use Espo\Core\Container;
use Espo\Modules\SnapclaritySync\Core\Listeners\AutomatedEmailsTrackingRelateToContact;
use Espo\Modules\SnapclaritySync\Core\Listeners\BenefitOptionRelateToContact;
use Espo\Modules\SnapclaritySync\Core\Listeners\BenefitRelateToContact;
use Espo\Modules\SnapclaritySync\Core\Listeners\MedicalIntakeRelateToContact;
use Espo\Modules\SnapclaritySync\Core\Listeners\UpdateOverallAssessmentRiskLevelContact;
use Espo\ORM\Entity;
use Espo\Core\Utils\Metadata;
use Espo\ORM\EntityManager;

class ContactUpdateEvent extends BaseEventHandler
{
    /**
     * @var Container
     */
    public $container;

    /**
     * @var
     */
    public $entityManager;

    /**
     * @var Metadata
     */
    public $metadata;

    /**
     * @var Entity
     */
    public $contact;

    /**
     * @var array
     */
    public $contactData = [];

    /**
     * @var array
     */
    public $options = [];

    /**
     * @param Container $container
     * @param EntityManager $entityManager
     * @param Metadata $metadata
     * @param Entity $contact
     * @param array $contactData
     * @param array $options
     */
    public function __construct(
        Container $container,
        EntityManager $entityManager,
        Metadata $metadata,
        Entity $contact,
        array $contactData = [],
        array $options = []
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
        $this->contact = $contact;
        $this->contactData = $contactData;
        $this->options = $options;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function initHandlers()
    {
        $this->eventHandlers = [
            UpdateOverallAssessmentRiskLevelContact::class
        ];
    }

}