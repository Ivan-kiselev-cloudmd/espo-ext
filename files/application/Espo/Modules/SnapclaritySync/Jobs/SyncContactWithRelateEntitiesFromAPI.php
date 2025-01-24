<?php

namespace Espo\Modules\SnapclaritySync\Jobs;

use Espo\Core\Container;
use Espo\Modules\SnapclaritySync\Services\ContactSync;
use stdClass;

/**
 * @property ContactSync contactSync
 */
class SyncContactWithRelateEntitiesFromAPI extends \Espo\Core\Jobs\Base
{

    /**
     * @var ContactSync
     */
    protected $contactSync;

    /**
     * @var Container
     */
    protected $container;

    /**
     * SyncContactWithRelateEntitiesFromAPI constructor.
     * @param Container $container
     * @param ContactSync $contactSync
     */
    public function __construct(
        Container $container,
        ContactSync $contactSync
    )
    {
        parent::__construct($container);
        $this->contactSync = $contactSync;
        $this->container = $container;
    }

    /**
     * @param $data
     * @param $targetId
     * @return bool
     */
    public function run($data, $targetId = null)
    {
        $integration = $this->container->get('entityManager')->getEntity('Integration', 'Contact');
        $additionalParameters = new stdClass();
        $additionalParameters->syncLastMinutes = $integration->get('syncLastMinutes') ?: 10;
        $additionalParameters->reportEmails = $integration->get('reportEmails') ?: [];
        $this->contactSync->syncContactWithRelateEntitiesFromAPI($additionalParameters);

        return true;
    }
}
